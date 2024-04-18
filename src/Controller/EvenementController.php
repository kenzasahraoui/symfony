<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

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
    public function shows(\App\Repository\EvenementRepository $EvenementRepository): Response
    {
        $Evenement = $EvenementRepository->findAll();
        return $this->render('Evenement/show.html.twig', [
            'Evenement' => $Evenement,
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


}