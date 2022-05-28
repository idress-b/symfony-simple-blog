<?php

namespace App\Controller;

use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfilController extends AbstractController
{
    /**
     * @Route("/compte/profil", name="profil")
     */
    public function index(Request $request,
                          EntityManagerInterface $em,
                          SluggerInterface $slugger,
                          string $uploadsAbsoluteDir,
                          string $uploadsRelativeDir): Response
    {
       $user = $this->getUser();
       $form = $this->createForm(ProfilType::class,$user)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $file= $form->get("file")->getData();
            if($file !== null) {
                $fileName = sprintf("%s_%s.%s",
                    $slugger->slug($file->getClientOriginalName()),
                    uniqid() ,
                    $file->getClientOriginalExtension()
                );

                $file->move($uploadsAbsoluteDir, $fileName);

                $user->setAvatar($fileName);
            }
            $em->flush();
            return $this->redirectToRoute("profil");
        }
        $avatar = $user->getAvatar();
        if ($avatar == null) {
            $avatar= 'avatar.jpg';
        }

        return $this->render('account/profil.html.twig', [
            'avatar'=>$avatar,
                    'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/compte/profil/delete-avatar", name="profil_delete")
     */
    public function deleteAvatar(EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $user->setAvatar('avatar.jpg');
        $em->flush();
        return $this->redirectToRoute("profil");
    }
}
