<?php

namespace App\Form;

use App\Entity\User;
use App\Form\FormExtension\RepeatedPasswordType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                        new Length(
                            [
                                'min' => 3,
                                'max' => 50,
                                'minMessage' => 'Le nom d\'utilisateur doit comporter au moins {{ limit }} caractères',
                                'maxMessage' => 'Le nom d\'utilisateur ne peut pas dépasser {{ limit }} caractères',
                            ]
                        )
                    ],
                    'attr' => ['autofocus' => true, 'placeholder' => 'John'],
                    'help' => 'Identifiant d\'authentification, il doit être unique',
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Adresse email',
                    'required' => true,
                    'constraints' => [
                        new Email()
                    ],
                    'attr' => ['placeholder' => 'johndoe@snowtricks.com'],
                    'help' => 'Une adresse email relié à aucun compte'
                ]
            )
            ->add(
                'plainPassword',
                RepeatedPasswordType::class
            )
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'choices' => [
                        'Utilisateur' => 'ROLE_USER',
                        'Administrateur' => 'ROLE_ADMIN'
                    ],
                    'multiple' => false,
                    'expanded' => false,
                    'label' => 'Rôle :',
                    'empty_data' => ['ROLE_USER'],
                    'required' => true
                ]
            );

        //roles field data transformer
        $builder->get('roles')
            ->addModelTransformer(
                new CallbackTransformer(
                    function ($rolesArray) {
                        // transform the array to a string
                        return count($rolesArray) ? $rolesArray[0] : null;
                    },
                    function ($rolesString) {
                        // transform the string back to an array
                        return [$rolesString];
                    }
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
                'constraints' => [
                    new UniqueEntity(
                        [
                            'fields' => ['username', 'email'],
                            'message' => ''
                        ]
                    ),
                ],
                'validation_groups' => ['registration'],
            ]
        );
    }
}
