<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Model\Auth\User;
use App\Model\Auth\CreateTestUser;
use App\Config\Environment;
use App\Model\Security\Voter\ManageVoters;
use App\Model\Security\Voter\Exception\VoterNotFoundException;
use App\Model\Security\Voter\Exception\AttributeMethodNotFoundException;

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

    protected function denyAccessUnlessGranted(string $attribute, $subject = null)
    {
        $user = $this->getUser();

        if (str_contains($attribute, 'ROLE')) 
        {
            if (!$user)
            {
                $this->session->getFlashBag()->add('error', 'You must log in to see this page.');
    
                return $this->redirectToRoute('/login');
            }
    
            if (!$user->hasRole($attribute))
            {
                $this->session->getFlashBag()->add('error', 'You don\'t have access to this page.');
    
                return $this->redirectToRoute('/');
            }

            return;
        }

        $manageVoters = new ManageVoters();

        try {
            if (!$manageVoters->isAccessAllowed($attribute, $subject, $user))
            {
                if (!$user) return $this->redirectToRoute('/login');
                
                return $this->redirectToRoute('/');
            }
        } catch (VoterNotFoundException | AttributeMethodNotFoundException $e)
        {
            print $this->twig->render('errors/exception.html.twig', ['message' => 'We could not confirm you permission to make this action.']);

            exit;
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