<?php

namespace App\Service;

use App\Entity\User;
use Exception;
use Symfony\Component\Form\FormInterface;

interface UserManagerInterface
{
    /**
     * Traitement du formulaire de création ou modification d'un User
     * @throws Exception
     * @param FormInterface<string> $form
     * @param User $user
     */
    public function userForm(FormInterface $form, User $user): void;
}