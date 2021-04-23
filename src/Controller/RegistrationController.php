<?php 

namespace App\Controller;

class RegistrationController extends AbstractController
{
    public function showRegistrationForm()
    {
        print $this->twig->render('auth/registration.html.twig');
    }
}