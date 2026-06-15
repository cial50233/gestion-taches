<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findBy([], ['id' => 'ASC']);

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/task/new', name: 'app_task_new')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_task');
        }

        return $this->render('task/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/task/{id}/complete', name: 'app_task_complete')]
    public function complete(
        Task $task,
        EntityManagerInterface $entityManager
    ): Response {
        $task->setCompleted(true);

        $entityManager->flush();

        return $this->redirectToRoute('app_task');
    }

    #[Route('/task/{id}/delete', name: 'app_task_delete')]
    public function delete(
        Task $task,
        EntityManagerInterface $entityManager
    ): Response {
        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('app_task');
    }

    #[Route('/task/{id}/edit', name: 'app_task_edit')]
    public function edit(
        Task $task,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_task');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form,
        ]);
    }

}