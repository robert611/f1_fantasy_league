<?php 

namespace App\Controller;

class IndexController extends AbstractController
{
    public function home()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        print $this->twig->render('home.html.twig');
    }
}