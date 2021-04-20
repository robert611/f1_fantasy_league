<?php 

namespace App\Controller;

abstract class AbstractController
{
    protected \Twig\Loader\FilesystemLoader $twigLoader;
    protected \Twig\Environment $twig;

    public function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../../templates');
        $this->twig = new \Twig\Environment($this->loader, ['debug' => true]);  
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        $this->twig->addGlobal('session', $_SESSION);
    }
}