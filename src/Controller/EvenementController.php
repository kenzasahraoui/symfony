<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\User;
use App\Form\EvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Rate;
use App\Form\RateType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement')]
    public function index(): Response
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }

    #[Route('/evenementadmin', name: 'app_evenement_admin')]
    public function shows(\App\Repository\EvenementRepository $EvenementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $Evenement = $EvenementRepository->findAll();

        $Evenement=$paginator->paginate(
            $Evenement,
            $request->query->getInt('page',1),
            3
        );
        return $this->render('Evenement/show.html.twig', [
            'Evenement' => $Evenement,
        ]);
    }

    #[Route('/evenementclient', name: 'app_evenement_client')]
    public function showsclient(\App\Repository\EvenementRepository $EvenementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $Evenement = $EvenementRepository->findAll();

        $Evenement=$paginator->paginate(
            $Evenement,
            $request->query->getInt('page',1),
            3
        );
        return $this->render('Evenement/showclient.html.twig', [
            'Evenement' => $Evenement,
        ]);
    }

     /**
     * @Route("/event/{id}/rate", name="rate_event")
     */
    public function rateEvent(Request $request, Evenement $event): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
    
        $rate = new Rate();
        $form = $this->createForm(RateType::class, $rate);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Set user ID to 2
            $user = $entityManager->getRepository(User::class)->find(28);
            $rate->setIdUser($user);
    
            // Set status based on the rate
            if ($rate->getRate() > 2) {
                $rate->setStatus('success');
            } else {
                $rate->setStatus('failure');
            }
    
            // Save the rate
            $entityManager->persist($rate);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_evenement_client');
        }
    
        // Render the rate form
        return $this->render('Evenement/rate_event.html.twig', [
            'rate' => $rate,
            'form' => $form->createView(),
        ]);
    }

    
    #[Route('/evenement/ajouter', name: 'app_evenement_ajouter')]
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
    
        $form = $this->createForm(EvenementType::class, $evenement);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                  // Gérer l'upload de l'image
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                // Déplacez le fichier vers le répertoire où vous souhaitez stocker les images
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer les exceptions liées à l'upload (par exemple, écrire dans les logs)
                }

                // Mettez à jour l'entité Work avec le nom de fichier de l'image
                $evenement->setImage($newFilename);
            }
                $entityManager->persist($evenement);
                $entityManager->flush();
    
                $this->addFlash('success', 'L\'événement a été ajouté avec succès.');
    
                return $this->redirectToRoute('app_evenement_admin');
            } else {
                // Ajouter un message d'erreur spécifique pour l'heure
                $this->addFlash('error', 'Il y a des erreurs dans le formulaire.');
            }
        }
    
        return $this->render('evenement/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/evenement/modifier/{id}', name: 'app_evenement_modifier')]
    public function modifier(Evenement $evenement, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->flush();
    
                $this->addFlash('success', 'L\'événement a été modifié avec succès.');
    
                return $this->redirectToRoute('app_evenement_admin');
            } else {
                // Ajouter un message d'erreur spécifique pour l'heure
                $this->addFlash('error', 'Il y a des erreurs dans le formulaire.');
            }
        }
    
        return $this->render('evenement/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }

#[Route('/evenement/supprimer/{id}', name: 'app_evenement_supprimer')]
public function supprimer(Evenement $evenement, EntityManagerInterface $entityManager): Response
{
    $entityManager->remove($evenement);
    $entityManager->flush();

    $this->addFlash('success', 'L\'événement a été supprimé avec succès.');

    return $this->redirectToRoute('app_evenement_admin');
}

#[Route("/save_location", name: "save_location", methods: ["POST"])]
    public function saveLocation(Request $request, LoggerInterface $logger): Response
    {
        // Récupérer la localisation à partir des données du formulaire
        $userLocation = $request->request->get('userLocation');
        
        // Vous pouvez ensuite traiter $userLocation selon vos besoins, par exemple, le stocker en base de données
        
        // Exemple pour le logger pour le moment
        $logger->info('Location saved: '.$userLocation);

        // Vous pouvez rediriger l'utilisateur vers une autre page ou retourner une réponse JSON
        $response = new JsonResponse([
            'message' => 'Location saved successfully',
            'location' => $userLocation
        ]);
        
        $renderedView = $this->render('Evenement/geo.html.twig', [
            'userLocation' => $userLocation
        ]);
        
        // Créer une réponse combinée
        $combinedResponse = new Response();
        $combinedResponse->setContent($renderedView->getContent() . $response->getContent());
        
        // Définir le type de contenu comme HTML
        $combinedResponse->headers->set('Content-Type', 'text/html');
        
        // Retourner la réponse combinée
        return $combinedResponse;
    }


}