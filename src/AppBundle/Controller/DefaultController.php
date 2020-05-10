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
