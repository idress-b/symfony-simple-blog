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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, PostRepository $repository, PaginatorInterface $paginator): Response
    {
        $donnees = $repository->findBy([], ['publishedAt' => 'desc']);
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
    // les deux fonctions suivantes sont désormais prises en charge dans le panneau d'administration easyAdmin
    /*
    /**
     * @Route("/publier-article", name="blog_create")

    public function create(EntityManagerInterface $em,
                           Request $request,
                           SluggerInterface $slugger,
                           string $uploadsAbsoluteDir,
                           string $uploadsRelativeDir)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class,$post,["validation_groups" =>["Default", "create"]])
                     ->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get("file")->getData();
            $fileName = sprintf("%s_%s.%s",
                $slugger->slug($file->getClientOriginalName()),
                uniqid() ,
                $file->getClientOriginalExtension()
        );

            $file->move($uploadsAbsoluteDir, $fileName);

            $post->setImage($uploadsRelativeDir ."/" .$fileName);
            $post->setAuthor($this->getUser());
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute("blog_read",["id"=> $post->getId()]);
        }
        return $this->render("blog/create.html.twig",[

            "form" => $form->createView()
        ]);
    }
      */
    /*
    /**
     * @Route("/modifier-article/{id}", name="blog_update")

    public function update(Post $post,
                           EntityManagerInterface $em,
                           Request $request,
                           SluggerInterface $slugger,
                           string $uploadsAbsoluteDir,
                           string $uploadsRelativeDir)
    {

        $form = $this->createForm(PostType::class,$post)->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){


            $file = $form->get("file")->getData();
            if($file !== null) {
                $fileName = sprintf("%s_%s.%s",
                    $slugger->slug($file->getClientOriginalName()),
                    uniqid() ,
                    $file->getClientOriginalExtension()
                );

                $file->move($uploadsAbsoluteDir, $fileName);

                $post->setImage($uploadsRelativeDir ."/" .$fileName);
            }

            $em->flush();
            return $this->redirectToRoute("blog_read",["id"=> $post->getId()]);
        }
        return $this->render("blog/update.html.twig",[

            "form" => $form->createView()
        ]);
    } */
}
