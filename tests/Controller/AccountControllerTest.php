<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AccountControllerTest extends WebTestCase
{
    //tester si  les users non connectés sont redirigés
    public function testPageRedirectToLogin(): void
    {

        $client = static::createClient();
        $crawler = $client->request('GET', '/compte/nouvel-article');

        $this->assertResponseRedirects('/login');
    }

    public function testConnectedUserCanAccesPage(): void
    {

        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@admin.com');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/compte/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Gestion de votre compte');
    }
}
