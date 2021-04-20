<?php 

namespace App\Controller;

class IndexController extends AbstractController
{
    public function home()
    {
        print $this->twig->render('home.html.twig');
    }
}