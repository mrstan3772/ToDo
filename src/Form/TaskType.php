<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                null,
                [
                    'label' => 'Titre'
                ]
            )
            ->add(
                'content',
                TextareaType::class,
                [
                    'label' => 'Contenu de la tâche',
                    'constraints' => [
                        new NotBlank(),
                        new Length(
                            [
                                'min' => 30,
                                'max' => 5000,
                                'minMessage' => 'La description doit comporter au moins {{ limit }} caractères',
                                'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères',
                            ]
                        )
                    ],
                    'attr' => [
                        'placeholder' => "Écrivez le contenu ici...",
                        'title' => 'Contenu de la tâche',
                        'minlenght' => 30,
                        'maxlength' => 5000
                    ],
                    'help' => 'La description de la tâche doit contenir entre 30 et 5000 caractères.',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Task::class,
            ]
        );
    }
}
