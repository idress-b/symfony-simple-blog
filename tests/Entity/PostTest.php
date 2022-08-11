<?php

namespace App\Tests\Entity;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostTest extends KernelTestCase
{

    public function getEntity()
    {
        $user = static::getContainer()->get('doctrine.orm.entity_manager')->find(User::class, 1);

        $article = new Post;
        $article->setTitle('titre_Essai')
            ->setContent('contenu article')

            ->setAuthor($user);

        return $article;
    }

    public function assertHasErrors($code, int $number = 0)
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $errors = $container->get('validator')->validate($code);

        $this->assertCount($number, $errors);
    }

    public function testValidEntity(): void
    {

        $this->assertHasErrors($this->getEntity());
    }

    public function testTitleOneLetterNotValid(): void
    {
        $this->assertHasErrors($this->getEntity()->setTitle('a'), 1);
        $this->assertHasErrors($this->getEntity()->setTitle('1'), 1);
    }

    public function testEmptyTitleNotValid(): void
    {
        $this->assertHasErrors($this->getEntity()->setTitle(''), 2); //retourne deux erreur 1 parce c'est vide et 2 c'est moins de 2 caractères
    }

    public function testEmptyContentNotValid()
    {
        $this->assertHasErrors($this->getEntity()->setContent(''), 2);
    }

    public function testContentHasTooFewLettersNotValid(): void
    {
        $this->assertHasErrors($this->getEntity()->setContent('123456789'), 1);
        $this->assertHasErrors($this->getEntity()->setContent('abcdefgt'), 1);
        $this->assertHasErrors($this->getEntity()->setContent('A3'), 1);
        $this->assertHasErrors($this->getEntity()->setContent('a'), 1);
    }
}
