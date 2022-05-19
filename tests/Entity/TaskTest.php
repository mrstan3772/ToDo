<?php

namespace Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tests\Traits\TaskTrait;

class TaskTest extends WebTestCase
{
    private $validator, $task;

    public function setUp(): void
    {
        $this->validator = static::getContainer()->get(ValidatorInterface::class);
        $this->task = $this->getTask(new Task());
        $this->date = new DateTimeImmutable();
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


    public function assertHasError(Task $task, int $number = 0): void
    {
        $validator = static::getContainer()->get(ValidatorInterface::class);
        $errors = $validator->validate($task);
        $messages = [];

        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    /**
     * @throws ReflectionException
     */
    public function set($entity, $value, $propertyName = 'id')
    {
        $class = new ReflectionClass($entity);
        $property = $class->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($entity, $value);
    }

    public function testCreatedAt(): void
    {
        $task = new Task();

        $newDate = new DateTimeImmutable(date('2022-01-01 00:00:00'));

        $task->setCreatedAt($newDate);

        $this->assertEquals($newDate, $task->getCreatedAt());
    }

    public function testIsDone(): void
    {
        $task = new Task();

        $task->setIsDone(false);

        $this->assertEquals(false, $task->getIsDone());
    }

    public function testIsPassToDone(): void
    {
        $task = new Task();

        $task->setIsDone(false);
        $task->toggle(true);

        $this->assertEquals(true, $task->getIsDone());
        $this->assertEquals(true, $task->isDone());
    }

    public function testIsPassToNotDone(): void
    {
        $task = new Task();

        $task->setIsDone(true);
        $task->toggle(false);

        $this->assertEquals(false, $task->getIsDone());
    }

    public function testUser(): void
    {
        $task = new Task();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(['username' => 'stanley']);

        $task->setUser($user);

        $this->assertInstanceOf(User::class, $task->getUser());
        $this->assertEquals($user->getId(), $task->getUser()->getId());
    }

    public function testNotBlankTaskTitle()
    {
        $this->assertHasError($this->task, 0);
    }

    public function testBlankTaskTitle()
    {
        $task = $this->task;
        $task->setTitle('');
        $this->assertHasError($task, 1);
    }

    public function testNotBlankTaskContent()
    {
        $this->assertHasError($this->task, 0);
    }

    public function testBlankTaskContent()
    {
        $task = $this->task;
        $task->setContent('');
        $this->assertHasError($task, 1);
    }

    public function testTitle(): void
    {
        $title = 'Curabitur Aliquet';
        $task = $this->task;
        $this->task->setTitle($title);
        $this->assertSame($title, $task->getTitle());
    }

    /**
     * @throws ReflectionException
     */
    public function testId(): void
    {
        $task = $this->task;
        $this->set($task, 1);
        $this->assertSame(1, $task->getId());
    }
}
