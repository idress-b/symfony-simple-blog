<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author',TextType::class, [
                "label"=>"pseudo  ",
                'constraints'=>new Length([
        'min'=>2,
        'max'=>30
    ])
            ])
            ->add('content',TextareaType::class,[
                'label' => 'Votre commentaire:',
                'constraints'=>new Length([
                    'min'=>2,
                    'max'=>250
                ])
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
