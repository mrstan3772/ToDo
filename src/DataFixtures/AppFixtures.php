<?php

namespace App\DataFixtures;

use App\Factory\TaskFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $created_admin_user = UserFactory::createOne(
            [
                'email' => 'stanlj8250@gmail.com',
                'username' => 'stanley',
                'roles' => ["ROLE_ADMIN"],
            ]
        );

        TaskFactory::createMany(
            25,
            [
                'user' => $created_admin_user,
                'isDone' => false,
            ]
        );

        $created_sample_user = UserFactory::createOne(
            [
                'email' => 'javons64@johns.net',
                'username' => 'Kaylin Ruka',
                'roles' => ["ROLE_USER"],
            ]
        );

        TaskFactory::createMany(
            25,
            [
                'user' => $created_sample_user
            ]
        );

        $created_users = UserFactory::createMany(10);

        TaskFactory::createMany(
            10,
            [
                'user' => null
            ]
        );

        foreach ($created_users as $user) {
            TaskFactory::createMany(
                25,
                [
                    'user' => $user
                ]
            );
        }

        $manager->flush();
    }
}