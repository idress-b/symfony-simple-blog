<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalPagesController extends AbstractController
{
    #[Route('/mentions-legales', name: 'app_mention_legale')]
    public function showLegalMentions(): Response
    {
        return $this->render('legal_pages/mentions_legales.html.twig');
    }

    #[Route('/cgu-cgv', name: 'app_cgu')]
    public function showLegalCGU(): Response
    {
        return $this->render('legal_pages/cgu-cgv.html.twig');
    }
}
