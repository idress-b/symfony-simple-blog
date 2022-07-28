<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $listCategory = ['HTML','Javascript','CSS','Métier','Motivation'];

        foreach ($listCategory as $item)
        {
           $cat = new Category;
           $cat->setName($item);
           $manager->persist($cat);
        }

        $manager->flush();
    }
}