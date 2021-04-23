<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class AbstractController
{
    protected \Twig\Loader\FilesystemLoader $twigLoader;
    protected \Twig\Environment $twig;
    protected Request $request;
    protected Session $session;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->session = new Session();
        $this->session->start();

        $this->loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../../templates');
        $this->twig = new \Twig\Environment($this->loader, ['debug' => true]);  
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        $this->twig->addGlobal('session', $this->session);
    }

    protected function redirectToRoute(string $uri)
    {
        header("Location: ${uri}");
    }
}