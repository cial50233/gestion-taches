<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

final class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }
    
    #[Route('/task/create', name: 'app_task_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $task = new Task();

        $task->setName('Apprendre Symfony');
        $task->setCompleted(false);

        $entityManager->persist($task);
        $entityManager->flush();

        return new Response('Tâche créée !');
    }
}