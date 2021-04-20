<?php 

namespace App\Controller;

class ErrorController extends AbstractController
{
    public function notFound()
    {
        print $this->twig->render('errors/404.html.twig');
    }
}