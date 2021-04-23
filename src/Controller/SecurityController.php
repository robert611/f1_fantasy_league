<?php 

namespace App\Controller;

class SecurityController extends AbstractController
{
    public function showLoginForm()
    {
        print $this->twig->render('auth/login.html.twig');
    }
}