<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager implements UserManagerInterface
{
    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordEncoderInterface $passwordEncoder)
    {
    }


    /**
     * @param FormInterface<string> $form
     * @param User $user
     */
    public function userForm(FormInterface $form, User $user): void
    {
        $plainPassword = $form->get('password')->getData();
        $password = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($password);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
