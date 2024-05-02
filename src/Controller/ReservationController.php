<?php

namespace App\Controller;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\QrCode;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Notifier\Recipient\Recipient;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use App\Services\EventService;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;



#[Route('/reservation')]
class ReservationController extends AbstractController
{
    private $mailerService;

    public function __construct(EventService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    #[Route('/reservation', name: 'app_reservation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findAll();
        $reservations=$paginator->paginate(
            $reservations,
            $request->query->getInt('page',1),
            3
        );

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
       
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request,EntityManagerInterface $entityManager): Response
    {
        
        $reservation = new Reservation();

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();

            $entityManager->persist($reservation);
            $entityManager->flush();

            // Generate QR code $qrCode = new QrCode($this->generateQrCodeContent($reservation));
            $qrCodePath = 'C:\Users\user\Desktop\3A7\semestre 2\PI\qrcode' . $reservation->getId() . '.png';


            // Send email with QR code
           
            $qrCodeContent = "Name: " . $reservation->getEvent()->getNom() . "\n";
            $qrCodeContent .= "Localisation: " . $reservation->getEvent()->getLocalisation() . "\n";
            $qrCodeContent .= "Date: " . $reservation->getEvent()->getDate() . "\n";
            $qrCodeContent .= "heure: " . $reservation->getEvent()->getHeure() . "\n";
            // Generate QR code
            $qrCode = new QrCode($qrCodeContent);
            $qrCode->setSize(300);
            $qrCode->setEncoding(new Encoding('UTF-8'));
            $writer = new PngWriter();
            $qrCodePath = 'barcodes\\' . $reservation->getId() . '.png'; // Provide the path where you want to save the QR code
            $qrCodeImage = $writer->write($qrCode);  
            $qrCodeImageContent = $qrCodeImage->getString();
            file_put_contents($qrCodePath, $qrCodeImageContent);

            $this->mailerService->sendEmailWithAttachment(
                'Reservation confirmation',
                'Thank you, ' . $form->get('nomparticipant')->getData() . ' , for reserving a spot at our event. Your reservation has been confirmed.',
                $email,$qrCodePath
                
            );

            return $this->redirectToRoute('app_reservation_index');
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/ExportPdf/{id}', name: 'app_pdf', methods: ['GET', 'POST'])]
    public function ExportPdf(Reservation $reservation) :Response
    {
          
          $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $html = $this->renderView('reservation/pdf.html.twig', [
            // Pass any necessary data to your Twig template
            'reservation' => $reservation,
        ]);

        $dompdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to browser (inline view)
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

}