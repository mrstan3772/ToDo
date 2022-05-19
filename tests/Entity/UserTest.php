<?php

namespace Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends WebTestCase
{

    private $validator;

    public function setUp(): void
    {
        $this->validator = static::getContainer()->get(ValidatorInterface::class);
        $this->task = new Task();
        $this->date = new DateTimeImmutable();
        $this->user = $this->getUser(new User());
    }
    
    protected function getUser(User $user): User
    {
        $user->setUsername('JohnDoe');
        $user->setPassword('_PassTest2022?');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail('test@test.com');
        return $user;
    }

    protected function getTask($task): Task
    {
        $task->setTitle('Titre Test');
        $task->setContent('Nulla quis lorem ut libero malesuada feugiat. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus.');
        $task->setCreatedAt(new DateTimeImmutable());
        $task->setUser($this->getUser(new User()));
        return $task;
    }

    public function assertHasError(User $user, int $number = 0): void
    {
        $errors = $this->validator->validate($user);
        $messages = [];

        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testUsername()
    {
        $this->assertHasError($this->user, 0);
        $this->assertEquals('JohnDoe', $this->user->getUsername());
        $user = $this->user->setUsername('');
        $this->assertHasError($user, 1);
    }

    public function testEmail()
    {
        $this->assertHasError($this->user, 0);
        $this->assertEquals('test@test.com', $this->user->getEmail());
        $user = $this->user->setEmail('');
        $this->assertHasError($user, 1);
        $user->setEmail('emailincorrectformat');
        $this->assertHasError($user, 1);
    }

    public function testRole()
    {
        $this->user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_ADMIN', $this->user->getRoles());
    }

    public function testPassword()
    {
        $this->assertEquals('_PassTest2022?', $this->user->getPassword());
    }

    public function testTask()
    {
        $user = $this->user;
        $task = $this->getTask($this->task);
        $user->addTask($task);
        $this->assertTrue($user->getTask()->contains($task));
        $user->removeTask($task);
        $this->assertFalse($user->getTask()->contains($task));
    }

    public function testSalt()
    {
        $this->assertNull($this->user->getSalt());
    }

    public function testUserIdentifier()
    {
        $user = $this->user;
        $this->assertEquals('test@test.com', $user->getUserIdentifier());
    }

    public function testEraseCredential()
    {
        $user = $this->user;
        $this->assertNull($user->eraseCredentials());
    }
}
