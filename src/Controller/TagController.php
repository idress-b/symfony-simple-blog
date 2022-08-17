<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class TagController extends AbstractController
{
    #[Route('/tag/{id}', name: 'app_tag')]
    public function showPostsWithTags(Request $request, PostRepository $postRepository, PaginatorInterface $paginator, Tag  $tag): Response
    {

        $donnees = $postRepository->findPostsByTag($tag->getId());
        $posts = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );


        return $this->render('tag/index.html.twig', [
            'posts' => $posts,
            'tag' => $tag
        ]);
    }
}
