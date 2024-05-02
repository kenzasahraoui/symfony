<?php

namespace App\Services;

use DateTime;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Swift_Attachment;

class EventService{

    private $mailer;

    public function __construct(MailerInterface $mailer,SessionInterface $session)
    {
        $this->mailer = $mailer;
    }



 
    public function sendEmail(string $subject,string $text,string $email)
    {
       
        $email = (new Email())
            ->from('ecoartteampi@gmail.com')
            ->to($email)
            ->subject($subject)
            ->text($text);
       //     ->html('<p>Contenu du message en HTML</p>');

       try{
        $this->mailer->send($email);
       } catch (\Exception $e) {
        dd($e->getMessage());
       }
    }

    public function sendEmailWithAttachment(string $subject, string $text, string $email, string $qrCodePath)
{
    // Create a new email instance
    $email = (new Email())
        ->from('ecoartteampi@gmail.com') // Set the sender email address
        ->to($email) // Set the recipient email address
        ->subject($subject) // Set the email subject
        ->text($text) // Set the email body as plain text
        ->attachFromPath($qrCodePath); // Attach the QR code to the email

    // Create an attachment for the QR code
    

    // Attach the QR code to the email

    // Attempt to send the email
    try {
        $this->mailer->send($email); // Send the email using the mailer instance
    } catch (\Exception $e) { // Catch any exceptions that occur during sending
        dd($e->getMessage()); // Dump and die with the error message
    }
}


// Usage


   


}