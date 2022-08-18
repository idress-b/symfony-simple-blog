<?php

namespace App\Form\dataTransformer;

use App\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagsDataTransformer implements DataTransformerInterface
{
   public function __construct(private EntityManagerInterface $em){}
   
    public function transform($value)
    {
        
        $value = $value->toArray();
        $tagAsString = implode(',',$value);
        return $tagAsString;
    }

    public function reverseTransform($value)
    {
        $tagsArray=explode(',', $value); // retourne un tableau de tags
        $tagsCollection = new ArrayCollection();

        foreach($tagsArray as $tagName){
            $tagName = trim($tagName);
            if ($tagName==''){continue;}
            $tag = $this->em->getRepository(Tag::class)->findOneBy(['name'=>trim($tagName)]);

            if($tag === null){
                $tag=new Tag;
                $tag->setName($tagName);
                $this->em->persist($tag);
            }

            $tagsCollection->add($tag);

        }

        

        return $tagsCollection  ;
    }
}