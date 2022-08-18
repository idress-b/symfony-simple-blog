<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo',TextType::class,[
                "label" => "Pseudo",
                 'constraints' => new Length(['min'=> 2]),
                 "attr" => [ 'placeholder' => 'saisir votre pseudo']
            ])
            ->add('email',EmailType::class,[
                "label" => "Email",

                "attr" => [ 'placeholder' => 'Saisir votre email']
            ])

            ->add('password',RepeatedType::class, [
                'type'=> PasswordType::class,
                'first_options' => [ 
                    'label'=> 'Saisir votre mot de passe',
                    'help'=>'le mot de passe doit contenir 6 caractères au minimum'],
                'second_options' => [ 'label' => 'Confirmer votre mot de passe'],
                'invalid_message' => "Le mot de passe et la confirmation doivent être identiques",
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6])
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
