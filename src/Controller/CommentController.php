<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Task;
use App\Form\CommentForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommentController extends Controller
{
    /**
     * @param Task $task
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/task/{task}/comment", name="comment")
     * @Method("POST")
     */
    public function store(Task $task, Request $request)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentForm::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setTask($task);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('message', 'Comment add');
        }

        return $this->redirectToRoute('task_show', [
            'task' => $task->getId(),
        ]);
    }
}
