<?php

namespace App\Controller;

use App\Entity\Post;

use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\FileUploader;
use Knp\Component\Pager\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/compte", name="account_")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig', []);
    }

    /**
     * @Route("/tous-les-articles", name="show_posts")
     */
    public function ShowPosts(PostRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        $donnees = $repository->findBy(['author' => $user], ['publishedAt' => 'desc']);
        $posts = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        return $this->render('account/show_posts.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/nouvel-article", name="new_post")
     */
    public function create(
        EntityManagerInterface $em,
        Request $request,
        FileUploader $fileUploader
    ) {


        $post = new Post();
        $form = $this->createForm(PostType::class, $post, ["validation_groups" => ["Default", "create"]])
            ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get("file")->getData();
            if ($file) {

                // $fileName = sprintf(
                //     "%s_%s.%s",
                //     $slugger->slug($file->getClientOriginalName()),
                //     uniqid(),
                //     $file->getClientOriginalExtension()
                // );



                // $file->move($uploadsAbsoluteDir, $fileName);
                $fileName = $fileUploader->upload($file);
                $post->setImage($fileName);
            }


            $post->setAuthor($this->getUser());
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute("blog_read", ["id" => $post->getId()]);
        }
        return $this->render("account/new_post.html.twig", [

            "form" => $form->createView(),

        ]);
    }

    /**
     * @Route("/modifier-article/{id}", name="update_post")
     */
    public function updatePost(
        EntityManagerInterface $em,
        Post $post,
        Request $request,
        SluggerInterface $slugger,
        string $uploadsAbsoluteDir,
        string $uploadsRelativeDir
    ) {

        $tempImg = $post->getImage();
        $tempFile = $uploadsAbsoluteDir . "/" . $tempImg;

        $form = $this->createForm(PostType::class, $post)
            ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get("file")->getData();
            if ($file) {
                $fileName = sprintf(
                    "%s_%s.%s",
                    $slugger->slug($file->getClientOriginalName()),
                    uniqid(),
                    $file->getClientOriginalExtension()
                );

                $file->move($uploadsAbsoluteDir, $fileName);

                if (file_exists($tempImg)) {
                    unlink($tempFile);
                }
                /* supprime le fichier du dossier ***/
                $post->setImage($fileName);
            }

            $post->setAuthor($this->getUser());

            $em->flush();

            return $this->redirectToRoute("blog_read", ["id" => $post->getId()]);
        }
        return $this->render("account/update_post.html.twig", [

            "form" => $form->createView(),
            "img" => $post->getImage()

        ]);
    }

    /** 
     * @Route("/supprimer-article/{id}", name="delete_post")
     */
    public function deletePost(
        EntityManagerInterface $em,
        PostRepository $postRepository,
        Post $post
    ) {
        $postRepository->remove($post);
        $em->flush();
        // 4 : redirect to route index
        return $this->redirectToRoute('account_show_posts');
    }
}
