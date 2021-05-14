<?php 

namespace App\Controller;

class AdminController extends AbstractController
{
    public function admin()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        print $this->twig->render('admin/index.html.twig');
    }
}