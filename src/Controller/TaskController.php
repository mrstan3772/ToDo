<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Service\TaskManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends AbstractController
{

    #[Route('/tasks', name: 'task_list')]
    public function listAction(TaskRepository $taskRepository) : Response
    {
        $tasks = $taskRepository->findBy([], ['createdAt' => 'desc']);

        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/tasks/done', methods: ['GET', 'POST'], name: 'task_list_done')]
    public function listDone(TaskRepository $taskRepository) : Response
    {
        $tasks = $taskRepository->findBy(['isDone' => true], ['createdAt' => 'desc']);

        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/tasks/todo', methods: ['GET', 'POST'], name: 'task_list_to_do')]
    public function listToDo(TaskRepository $taskRepository) : Response
    {
        $tasks = $taskRepository->findBy(['isDone' => false], ['createdAt' => 'desc']);

        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/tasks/create', name: 'task_create')]
    public function createAction(Request $request, TaskManagerInterface $taskManager) : Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $taskManager->createTask($task);
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage() . 'Erreur Système : veuillez ré-essayer');
                return $this->redirectToRoute('homepage');
            }

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit',  methods: ['GET', 'POST'], name: 'task_edit')]
    #[IsGranted('task_edit', subject: 'task')]
    public function editAction(Task $task, Request $request, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render(
            'task/edit.html.twig',
            [
                'form' => $form->createView(),
                'task' => $task,
            ]
        );
    }

    #[Route('/tasks/{id}/toggle', methods: ['GET', 'POST'], name: 'task_toggle')]
    public function toggleTaskAction(Task $task, TaskManagerInterface $taskManager) : Response
    {
        try {
            $taskManager->toggleTask($task);
        } catch (Exception $exception) {
            $this->addFlash('error', $exception->getMessage() . 'Erreur Système : veuillez ré-essayer');
            return $this->redirectToRoute('homepage');
        }

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete',  methods: ['GET', 'POST'], name: 'task_delete')]
    #[IsGranted('task_delete', subject: 'task')]
    public function deleteTaskAction(Task $task, EntityManagerInterface $em) : Response
    {   
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
