<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Service\FileUploaderNoSlug;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class PostCrudController extends AbstractCrudController
{


    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);}
        else{

            $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
                ->andWhere('entity.author = :user')
                ->setParameter('user', $this->getUser());
        }
            return $qb;
    }



    public function configureFields(string $pageName): iterable
    {
          
        return [
            AssociationField::new('author','Auteur')
                               ->hideOnForm(),
            TextField::new('title','Titre') ,
            AssociationField::new('category','Catégorie'),
            AssociationField::new('tags','tags'),
            ImageField::new('image')
                ->setBasePath('https://objectif-developpeur.s3.eu-west-3.amazonaws.com/')
                ->setUploadDir('public/uploads/')
                ->setUploadedFileNamePattern('[slug]-[uuid].[extension]')
                ->setRequired(false)
                 ,
            TextEditorField::new('content','Contenu')
                ->setNumOfRows(30)
            ,

            DateField::new('publishedAt','Date')->hideOnForm(),
            AssociationField::new('comments','commentaires')
                ->hideOnForm(),
            BooleanField::new('isPublished','Validé')


        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('articles')
            ->setEntityLabelInSingular('article')
            ->setDefaultSort([ 'publishedAt' => 'DESC'])
            ->showEntityActionsInlined();
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if(!$entityInstance instanceof Post) return;

        $entityInstance->setAuthor($this->getUser());
        parent::persistEntity($entityManager, $entityInstance);
    }

}
