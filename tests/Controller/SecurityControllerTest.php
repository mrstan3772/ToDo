<?php

namespace Tests\Controller;

use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testLogout(): void
    {
        /** @var \App\Repository\UserRepository $userRepository*/
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'stanley']);

        if (!$testUser instanceof UserInterface) {
            throw new Exception("Il n'y a pas de testUser pour se connecter", 1);
        }

        $this->client->loginUser($testUser);

        $crawler = $this->client->request('GET', '/');
        $link = $crawler->selectLink('Se déconnecter')->link();
        $this->client->click($link);

        $this->client->followRedirect();

        $this->assertStringContainsString('Se connecter', '' . $this->client->getResponse()->getContent());
    }

    public function testLogin(): void
    {
        $this->client->restart();

        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Connexion')->form(
            [
                'username' => 'stanley',
                'password' => '&P@ssowrd3772_',
            ]
        );

        $crawler = $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertStringContainsString('Se déconnecter', '' . $this->client->getResponse()->getContent());
    }
}
