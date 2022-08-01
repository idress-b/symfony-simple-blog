<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, PostRepository $repository, PaginatorInterface $paginator): Response
    {
        // $donnees = $repository->findBy([], ['publishedAt' => 'desc']);
        $donnees = $repository->findPostsWithCommentsAndTags();
        $posts = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
       
        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/article-{id}", name="blog_read")
     */
    public function read(EntityManagerInterface $em, Post $post, Request $request): Response
    {
        $comment = new Comment();
        $comment->setPost($post);

        // création du formulaire de recueil de commentaires

        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute("blog_read", ["id" => $post->getId()]);
        }


        return $this->render("blog/read.html.twig", [
            "post" => $post,

            "form" => $form->createView()
        ]);
    }
}
