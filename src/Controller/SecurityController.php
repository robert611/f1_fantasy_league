<?php 

namespace App\Controller;

use App\Model\Auth\User;
use App\Model\Auth\Authentication;
use App\Model\Database\Repository\LoginRepository;

class SecurityController extends AbstractController
{
    public function showLoginForm()
    {
        print $this->twig->render('auth/login.html.twig');
    }

    public function login()
    {
        $username = $this->request->get('username');
        $password = $this->request->get('password');
      
        $authentication = new Authentication();

        if (!$user = $authentication->getUser($username)) {
            $this->session->getFlashBag()->add('login_error', 'User with this username does not exist');

            return $this->redirectToRoute('/login');
        }
        
        if (!$authentication->isPasswordCorrect($user, $password)) {
            $this->session->getFlashBag()->add('login_error', 'Given password is incorrect');

            return $this->redirectToRoute('/login');
        }

        $userObject = new User($user);

        $this->session->set('user', $userObject);

        (new LoginRepository)->saveLogin($user['id']);

        return $this->redirectToRoute('/');
    }

    public function logout()
    {
        $this->session->remove('user');

        $this->redirectToRoute('/login');
    }
}