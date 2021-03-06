<?php

namespace App\Controller;

use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\FileUploader;

class ProfilController extends AbstractController
{
    /**
     * @Route("/compte/profil", name="profil")
     */
    public function index(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        FileUploader $fileUploader
        
    ): Response {
        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class, $user)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get("file")->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file);

                $user->setAvatar($fileName);
            }
            $em->flush();
            return $this->redirectToRoute("profil");
        }
        $avatar = $user->getAvatar();
        if ($avatar == null) {
            $avatar = 'avatar.jpg';
        }

        return $this->render('account/profil.html.twig', [
            'avatar' => $avatar,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/profil/delete-avatar", name="profil_delete")
     */
    public function deleteAvatar(EntityManagerInterface $em,  string $uploadsAbsoluteDir)
    {
        $user = $this->getUser();

        // On tocke l'ancienne image et on la supprime du dossier
        $tempImg = $user->getAvatar();
        $tempFile = $uploadsAbsoluteDir . "/" . $tempImg;
        unlink($tempFile);

        // on met une image d'avatar vide
        $user->setAvatar('avatar.jpg');
        $em->flush();
        return $this->redirectToRoute("profil");
    }
}
