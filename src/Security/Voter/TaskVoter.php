<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TaskVoter extends Voter
{
    private const DELETE = 'delete';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::DELETE])) {
            return false;
        }

        // only vote on `Task` objects
        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        /** @var Task $task */
        $task = $subject;

        if ($user === $task->getUser()) {
            return true;
        }

        if ($this->security->isGranted('ROLE_ADMIN') && $task->getUser() === null) {
            return true;
        }
        
        return false;
    }
}