<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/home")
     */

    public function RedirectAction()
    {
        $autochecker=$this->container->get('security.authorization_checker');
        if($autochecker->isGranted('ROLE_ADMIN'))
        {
            return $this->redirect($this->generateUrl('afficherNews'));
        }

        else
        {
            return $this->redirect($this->generateUrl('afficher'));
        }
    }

    public function loginMobileAction($username, $password)
    {
        $user_manager = $this->get('fos_user.user_manager');
        $factory = $this->get('security.encoder_factory');
        $data = [
            'type' => 'validation error',
            'title' => 'There was a validation error',
            'errors' => 'username or password invalide'
        ];
        $response = new JsonResponse($data, 400);
        $user = $user_manager->findUserByUsername($username);
        if(!$user)
            return $response;
        $encoder = $factory->getEncoder($user);
        $bool = ($encoder->isPasswordValid($user->getPassword(),$password,$user->getSalt())) ? "true" : "false";
        if($bool=="true")
        {
            $role= $user->getRoles();
            $data=array('type'=>'Login succeed',
                'id'=>$user->getId(),
                'firstname'=>$user->getFirstname(),
                'lastname'=>$user->getLastname(),
                'role'=>$user->getRoles(),
                'email'=>$user->getEmail(),
                'image'=>$user->getImage());
            $response = new JsonResponse($data, 200);
            return $response;
        }
        else
        {
            return $response;
        }
        // return array('name' => $bool);
    }
}