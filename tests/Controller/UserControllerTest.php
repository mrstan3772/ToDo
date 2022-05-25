<?php

namespace Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class UserControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function logUserTest(string $username, KernelBrowser $client): User
    {
        /** @var \App\Repository\UserRepository $userRepository*/
        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(['username' => $username]);

        if (!$user instanceof UserInterface) {
            throw new Exception("Il n'y a pas de testUser pour se connecter", 1);
        }

        $this->client->loginUser($user);

        return $user;
    }

    /**
     * @param array<string, string|int> $criteria
     */
    public function findUser(array $criteria): User
    {
        /** @var \App\Repository\UserRepository $userRepository*/
        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy($criteria);

        if (!$user instanceof User) {
            throw new Exception("L\'utilisateur de test n'existe pas", 1);
        }

        return $user;
    }

    public function testCreateUser(): void
    {
        $this->logUserTest('stanley', $this->client);

        $crawler =  $this->client->request('GET', '/users/create');

        $testUserRoles = $this->findUser(['username' => 'stanley'])->getRoles();

        $this->assertContains('ROLE_ADMIN', $testUserRoles, 'Le user connecté n\'est pas admin');

        $form = $crawler->selectButton('Ajouter')->form(
            array(
                'user[username]' => 'John Doe',
                'user[email]' => 'johndoe@example.fr',
                'user[plainPassword][pass]' => 'P@ssword1',
                'user[plainPassword][confirm]' => 'P@ssword1',
                'user[roles]' => 'ROLE_ADMIN'
            )
        );

        $crawler =  $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertStringContainsString('John Doe', '' .  $this->client->getResponse()->getContent());
        $this->assertStringContainsString('utilisateur a bien été ajouté', '' .  $this->client->getResponse()->getContent());
    }

    public function testEditUser(): void
    {
        $user = $this->logUserTest('stanley', $this->client);

        $user_id = (string) $user->getId();

        $crawler =  $this->client->request('GET', '/users/' . $user_id . '/edit');

        $testUserRoles = $this->findUser(['username' => 'stanley'])->getRoles();
        $this->assertContains('ROLE_ADMIN', $testUserRoles, 'Le user connecté n\'est pas admin');

        $form = $crawler->selectButton('Modifier')->form(
            array(
                'user[username]' => 'Jane Doe',
                'user[email]' => 'janedoe@example.fr',
                'user[plainPassword][pass]' => 'P@ssword1',
                'user[plainPassword][confirm]' => 'P@ssword1',
                'user[roles]' => 'ROLE_ADMIN'
            )
        );

        $crawler =  $this->client->submit($form);

        $this->client->followRedirect();
        $this->assertStringContainsString('utilisateur a bien été modifié', '' .  $this->client->getResponse()->getContent());

        $this->logUserTest('Jane Doe', $this->client);

        $crawler =  $this->client->request('GET', '/users/' . $user_id . '/edit');
        $this->assertStringContainsString('Jane Doe', '' .  $this->client->getResponse()->getContent());
    }
}