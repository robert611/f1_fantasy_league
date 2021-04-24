<?php 

namespace App\Controller;

use App\Model\Registration;
use App\Model\Database\Repository\UserRepository;

class RegistrationController extends AbstractController
{
    public function showRegistrationForm()
    {
        print $this->twig->render('auth/registration.html.twig');
    }

    public function register()
    {
        $username = $this->request->get('username');
        $email = $this->request->get('email');
        $password = $this->request->get('password');
        $repeatedPassword = $this->request->get('password-repeat');

        $registrationModel = new Registration();

        if (!$registrationModel->validateUserData($username, $email, $password, $repeatedPassword)) {
            $formErrors = $registrationModel->getValidationErrors();

            foreach ($formErrors as $error) $this->session->getFlashBag()->add('registration_error', $error);

            $this->redirectToRoute('/register');
        }

        $password = password_hash($password, PASSWORD_BCRYPT);

        $userRepository = new UserRepository();

        $userRepository->saveUser($username, $email, $password, '["ROLE_USER"]');

        $this->session->getFlashBag()->add('success', 'Registration is complete, now you can log in!');

        $this->redirectToRoute('/login');
    }
}