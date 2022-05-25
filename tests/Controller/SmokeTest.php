<?php

namespace Tests\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

class SmokeTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider provideUrlsAdmin
     */
    public function testPageIsSuccessful(string $pageName, string $url): void
    {
        /** @var \App\Repository\UserRepository $userRepository*/
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'stanley']);

        if (!$testUser instanceof UserInterface) {
            throw new Exception("Il n'y a pas de testUser pour se connecter", 1);
        }

        $this->client->loginUser($testUser);

        //$client->catchExceptions(false);
        $this->client->request('GET', $url);

        $response =  $this->client->getResponse();

        $this->assertTrue(
            $response->isSuccessful() || 301 || 302,
            sprintf(
                'La page "%s" devrait être accessible, mais le code HTTP est "%s".',
                $pageName,
                $response->getStatusCode()
            )
        );
    }

    /**
     * @dataProvider provideUrlsUSer
     */
    public function testPageIsUnsuccessful(string $pageName, string $url): void
    {
        /** @var \App\Repository\UserRepository $userRepository*/
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'Kaylin Ruka']);

        if (!$testUser instanceof UserInterface) {
            throw new Exception("Il n'y a pas de testUser pour se connecter", 1);
        }

        $this->client->loginUser($testUser);

        $this->client->request('GET', $url);

        $response = $this->client->getResponse();

        $this->assertTrue(
            $response->isForbidden(),
            sprintf(
                'La page "%s" devrait être inaccessible, mais le code HTTP est "%s".',
                $pageName,
                $response->getStatusCode()
            )
        );
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function provideUrlsAdmin(): array
    {
        // $userRepository = static::getContainer()->get(UserRepository::class);
        // $user = $userRepository->findAll();
        // $user_id = (string) $user[0]->getId();

        // $taskRepository = static::getContainer()->get(TaskRepository::class);
        // $task = $taskRepository->findAll();
        // $task_id = (string) $task[0]->getId();

        return [
            'homepage' => ['homepage', '/'],
            'security_login' => ['securityLogin', '/login'],
            'task_list' => ['taskListe', '/tasks'],
            'task_list_done' => ['taskListeDone', '/tasks/done'],
            'task_list_todo' => ['taskListeTodo', '/tasks/todo'],
            'task_create' => ['taskCreate', '/tasks/create'],
            // 'task' => ['task', '/tasks/' . $task_id . '/edit'],
            'user_list' => ['userListe', '/users'],
            'user_create' => ['userCreate', '/users/create'],
            // 'user' => ['user', '/users/' . $user_id . '/edit']
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function provideUrlsUser(): array
    {
        // $userRepository = static::getContainer()->get(UserRepository::class);
        // $user = $userRepository->findAll();
        // $user_id = (string) $user[0]->getId();

        return [
            'user_list' => ['userListe', '/users/'],
            'user_create' => ['userCreate', '/users/create'],
            // 'user' => ['user', '/users/' . $user_id . '/edit']
        ];
    }
}
