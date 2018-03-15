<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\CommentForm;
use App\Form\TaskForm;
use App\Repository\TaskRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TaskController extends Controller
{
    /**
     * @param TaskRepository $taskRepository
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/tasks", name="tasks")
     * @Method("GET")
     */
    public function index(TaskRepository $taskRepository)
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findAllWithMaxLengthComment(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/task", name="task_store")
     * @Method({"GET", "POST"})
     */
    public function save(Request $request)
    {
        $task = new Task();

        $form = $this->createForm(TaskForm::class, $task);
        $form->handleRequest($request);

        $statusCode = 200;

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('message', 'Task created');

            return $this->redirectToRoute('task_show', [
                'task' => $task->getId(),
            ]);
        } elseif ($request->isMethod('POST')) {
            $statusCode = 422;
        }

        return $this->render('task/form.html.twig', [
            'form' => $form->createView(),
        ])->setStatusCode($statusCode);
    }

    /**
     * @param Request $request
     * @param Task $task
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/task/{task}", name="task_update")
     * @Method({"GET", "PUT"})
     */
    public function update(Request $request, Task $task)
    {
        $form = $this->createForm(TaskForm::class, $task, [
            'action' => $this->generateUrl('task_update', ['task' => $task->getId()]),
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('message', 'Comment updated');

            return $this->redirectToRoute('task_show', [
                'task' => $task->getId(),
            ]);
        }

        return $this->render('task/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Task $task
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/task/{task}/show", name="task_show")
     * @Method("GET")
     */
    public function show(Task $task)
    {
        $form = $this->createForm(CommentForm::class);

        return $this->render('task/show.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }
}
