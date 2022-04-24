<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskManager implements TaskManagerInterface
{
    public function __construct(private EntityManagerInterface $entityManager, private TokenStorageInterface $tokenStorage)
    {
    }

    public function createTask(Task $task): void
    {
        $token = $this->tokenStorage->getToken();
        if (!$token instanceof TokenInterface) {
            throw new Exception();
        }
        $user = $token->getUser();
        if (!$user instanceof User) {
            throw new Exception();
        }
        $task->setUser($user);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function toggleTask(Task $task): void
    {
        $task->toggle(!$task->isDone());

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
}
