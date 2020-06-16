<?php

namespace ReclamationBundle\Controller;

use ReclamationBundle\Entity\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TypeController extends Controller
{
    public function AjoutTypeAction( \Symfony\Component\HttpFoundation\Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $type = new Type();

        $form = $this->createForm('ReclamationBundle\Form\TypeType', $type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($type);
            $em->flush();
            return $this->redirectToRoute('Type_Afficher');
        }
        return $this->render('@Reclamation/Type/AjouterType.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    public function AfficheTypeAction()
    {
        $m = $this->getDoctrine()->getManager();
        $type = $m->getRepository("ReclamationBundle:Type")->findAll();
        return $this->render('@Reclamation/Type/AfficherType.html.twig', array(
            'type' => $type,
        ));
    }

    public function deleteTypeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $types = $em->getRepository("ReclamationBundle:Type")->find($id);
        $em->remove($types);
        $em->flush();
        return $this->redirectToRoute('Type_Afficher');

    }

}
