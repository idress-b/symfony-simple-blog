<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_register")
     */
    public function index(Request $request, UserPasswordHasherInterface $encoder, EntityManagerInterface $em): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $hashedPassword = $encoder->hashPassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($hashedPassword);
            /**** met une image d'avatar par défaut ***/
            $user->setAvatar('avatar.jpg');

            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Votre compte a été crée avec succès, vous pouvez désormais vous connecter.');
            return $this->redirectToRoute("profil");
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
