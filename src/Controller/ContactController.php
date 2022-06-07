<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\Exception\TransportExceptionInterface;

class ContactController extends AbstractController
{
    /**
     * @Route("/nous-contacter", name="app_contact")
     */
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $contact = $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
                ->from($contact->get('email')->getData())
                ->to('monsite@example.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Message depuis le formulaire de contact de la part de ' . $contact->get('name')->getData())
                ->text($contact->get('message')->getData());

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                dd('probleme ' . $e);
            }


            return $this->redirectToRoute('app_contact');
        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
