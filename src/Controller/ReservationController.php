<?php

namespace App\Controller;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Endroid\QrCode\QrCode;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Notifier\Recipient\Recipient;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    
    #[Route('/reservation', name: 'app_reservation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findAll();

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $reservation = new Reservation();

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            // Generate QR code
            $qrCode = new QrCode($this->generateQrCodeContent($reservation));
            $qrCodePath = 'C:\Users\user\Desktop\3A7\semestre 2\PI\qrcode' . $reservation->getId() . '.png';


            // Send email with QR code
            $this->sendEmailWithQrCode($mailer, $reservation->getEmail(), $qrCodePath);

            return $this->redirectToRoute('app_reservation_index');
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function generateQrCodeContent(Reservation $reservation): string
    {
        // Generate QR code content using reservation information
        $content = json_encode([
            'id' => $reservation->getId(),
            'nomparticipant' => $reservation->getNomparticipant(),
            // Add other reservation information as needed
        ]);

        return $content;
    }

    private function sendEmailWithQrCode(MailerInterface $mailer, string $recipient, string $qrCodePath): void
    {
        $email = (new Email())
            ->from('sahraouikinza121@gmail.com')
            ->to($recipient)
            ->subject('Your Reservation QR Code')
            ->text('Please find your reservation QR code attached.')
            ->attachFromPath($qrCodePath, 'reservation_qr_code.png');

        $mailer->send($email);
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