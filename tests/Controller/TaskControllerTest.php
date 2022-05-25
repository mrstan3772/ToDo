<?php

namespace Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskControllerTest extends WebTestCase
{
    private $client, $select_action, $task_id;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->select_action = 0;
        $this->task_id;
    }

    public function findNumberInString($subject): string
    {
        preg_match_all('!\d+!', $subject, $matches);

        return  $matches[0][0];
    }

    public function logUserTest(string $username): User
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
    public function findTask(array $criteria): Task
    {
        /** @var \App\Repository\TaskRepository $taskRepository*/
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taskRepository->findOneBy($criteria);
        if (!$task instanceof Task) {
            throw new Exception("La task de test n'existe pas", 1);
        }
        return $task;
    }

    public function testCreateTask(): void
    {
        $this->logUserTest('stanley', $this->client);

        //Test formulaire valide
        $crawler = $this->client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form(
            array(
                'task[title]' => 'Titre Tâche Test',
                'task[content]' => 'Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.',
            )
        );

        $crawler = $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertStringContainsString('Tâche Test', '' . $this->client->getResponse()->getContent());
        $this->assertStringContainsString('La tâche a été bien été ajoutée', '' . $this->client->getResponse()->getContent());

        //Test nouvelle tâche sur la page
        $task = $this->findTask(['title' => 'Titre Tâche Test']);
        $this->assertEquals('Titre Tâche Test', $task->getTitle());

        //Test CreatedAt
        // $task = $this->findTask(['id' => '7']);
        $now = new DateTimeImmutable();
        $taskDate = $task->getCreatedAt();
        if (!$taskDate instanceof DateTimeImmutable) {
            throw new Exception("Erreur sur la date de création de test", 1);
        }
        $interval = $now->diff($taskDate);
        $nbJour = $interval->h;
        $this->assertLessThanOrEqual('1', $nbJour, 'La date de création est fausse');
    }

    public function testEditTask(): void
    {
        $user = $this->logUserTest('stanley', $this->client);

        $task_id = (string) $user->getTask()->first()->getId();

        $crawler = $this->client->request('GET', '/tasks/' . $task_id . '/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Modifier', '' . $this->client->getResponse()->getContent());

        $form = $crawler->selectButton('Modifier')->form(
            array(
                'task[title]' => 'Titre Tâche Test Modifié',
                'task[content]' => 'Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus.',
            )
        );

        $crawler = $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertStringContainsString('Titre Tâche Test Modifié', '' . $this->client->getResponse()->getContent());
        $this->assertStringContainsString('La tâche a bien été modifiée.', '' . $this->client->getResponse()->getContent());
    }

    public function testToggleTask(): void
    {
        $this->logUserTest('stanley', $this->client);

        $crawler = $this->client->request('GET', '/tasks');

        $crawler->filter('div.thumbnail form:nth-child(1)')->each(
            function (Crawler $node, $i) {
                $href_path = $node->attr('action');
                $this->task_id = $this->findNumberInString($href_path);

                $task = $this->findTask(['id' => $this->task_id]);

                if ($task->getUser() === null) return;

                $username = $task->getUser()->getUsername();

                if ($username === 'stanley' && $this->select_action !== 1) {
                    $form = $node
                        ->selectButton('Marquer comme faite')
                        ->form();

                    $this->client->submit($form);

                    $this->client->followRedirect();
                    $this->assertStringContainsString('a bien été marquée comme faite', '' . $this->client->getResponse()->getContent());

                    $task = $this->findTask(['id' => $this->task_id]);
                    $this->assertEquals('1', $task->getIsDone(), 'La tâche 1 n\'est pas marquée terminée');

                    $this->select_action = 1;

                    return;
                }
            }
        );
    }

    public function testDeleteTask(): void
    {
        $user =  $this->logUserTest('Kaylin Ruka', $this->client);

        $crawler = $this->client->request('GET', '/tasks');

        $crawler->filter('div.thumbnail form:nth-child(2)')->each(
            function (Crawler $parentCrawler, $i) {
                $href_path = $parentCrawler->attr('action');
                $task_id = $this->findNumberInString($href_path);

                $task = $this->findTask(['id' => $task_id]);

                if ($task->getUser() === null) return;

                $username = $task->getUser()->getUsername();

                if ($username === 'Kaylin Ruka' && $this->select_action !== 1) {
                    $form = $parentCrawler
                        ->selectButton('Supprimer')
                        ->form();
                    $this->client->submit($form);

                    $this->assertTrue($this->client->getResponse()->isRedirection());

                    $this->client->followRedirect();
                    $this->assertStringContainsString('La tâche a bien été supprimée.', '' . $this->client->getResponse()->getContent());
    
                    $this->select_action = 1;

                    return;
                }
            }
        );
    }

    public function testDeleteTasknotCreated(): void
    {
        $user1 = $this->logUserTest('stanley', $this->client);

        /** @var \App\Repository\UserRepository $userRepository*/
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user2 = $userRepository->findOneBy(['username' => 'Kaylin Ruka']);

        $task = $user2->getTask()->first();
        $this->assertNotEmpty($task, 'La tâche à supprimer n\'existe pas');

        $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $this->assertTrue($this->client->getResponse()->isForbidden());
    }
}
