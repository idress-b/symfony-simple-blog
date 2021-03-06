<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('commentaires')
            ->setEntityLabelInSingular('commentaire')
            ->showEntityActionsInlined()
            ; // TODO: Change the autogenerated stub
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('author','Auteur') ,
            TextareaField::new('content','Contenu')->setMaxLength(120),
            AssociationField::new('post','article rattaché')->hideWhenUpdating(),
            DateField::new('postedAt','Ecrit le')->hideOnForm(),
            BooleanField::new('isValidatedByAdmin','Validé ?')
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters

            ->add(EntityFilter::new('post')
                ->setLabel('filtrer par article'))



            ;
    }
}
