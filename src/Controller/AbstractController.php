<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Model\Auth\User;
use App\Model\Auth\CreateTestUser;
use App\Config\Environment;

abstract class AbstractController
{
    protected \Twig\Loader\FilesystemLoader $twigLoader;
    protected \Twig\Environment $twig;
    protected Request $request;
    protected Session $session;
    protected Environment $environment;

    public function __construct()
    {
        $this->environment = new Environment();
        $this->request = Request::createFromGlobals();
        $this->session = new Session();
        $this->session->start();

        $this->loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../../templates');
        $this->twig = new \Twig\Environment($this->loader, ['debug' => true]);  
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        $this->twig->addGlobal('session', $this->session);

        if ($this->request->headers->get('test_user') == true)
        {
            if ($this->getEnvironment() == 'test')
            {
                $userObject = CreateTestUser::getTestUser($this->request->headers->get('user_roles'));

                $this->session->set('user', $userObject);
            }
        }
    }

    protected function redirectToRoute(string $uri)
    {
        header("Location: ${uri}");

        exit;
    }

    protected function denyAccessUnlessGranted(string $role)
    {
        if (!$user = $this->getUser())
        {
            $this->session->getFlashBag()->add('error', 'You must log in to see this page.');

            return $this->redirectToRoute('/login');
        }

        if (!$user->hasRole($role))
        {
            $this->session->getFlashBag()->add('error', 'You don\'t have access to this page.');

            return $this->redirectToRoute('/');
        }
    }

    protected function getUser(): null | User
    {
        return $this->session->get('user');
    }

    protected function getEnvironment(): string
    {
        return $this->environment->getVariable('env');
    }
}