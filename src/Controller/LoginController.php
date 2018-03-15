<?php

namespace App\Controller;

use App\Form\LoginForm;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends Controller
{
    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
        return $this->render('login/index.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(false),
            'last_username' => $authenticationUtils->getLastUsername(),
            'form' => $this->createForm(LoginForm::class)->createView(),
        ]);
    }
}
