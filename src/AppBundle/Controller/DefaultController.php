<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
     * @Route("/back", name="back")
     */
    public function backAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('back.html.twig');
    }
    /**
     * @Route("/front", name="front")
     */
    public function frontAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('base.html.twig');
    }


    public function allusersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $ch="a:1:{i:0;s:14:\"ROLE_CHAUFFEUR\";}";
        $ch1="\"ROLE_CHAUFFEUR\"";
        $categories = $em->getRepository('AppBundle:User')->findBy(array('role'=>"chauffeur"));
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($categories, 'json');
        echo $jsonContent;
        return new Response($jsonContent);
    }

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
