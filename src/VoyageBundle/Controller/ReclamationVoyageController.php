<?php

namespace VoyageBundle\Controller;

use VoyageBundle\Entity\ReclamationVoyage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use VoyageBundle\Entity\Voyage;

/**
 * Reclamationvoyage controller.
 *
 */
class ReclamationVoyageController extends Controller
{
    /**
     * Lists all reclamationVoyage entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reclamationVoyages = $em->getRepository('VoyageBundle:ReclamationVoyage')->findAll();

        return $this->render('reclamationvoyage/index.html.twig', array(
            'reclamationVoyages' => $reclamationVoyages,
        ));
    }

    /**
     * Creates a new reclamationVoyage entity.
     *
     */
    public function newAction(Request $request,Voyage $voyage)
    {
        $reclamationVoyage = new Reclamationvoyage();
        $form = $this->createForm('VoyageBundle\Form\ReclamationVoyageType', $reclamationVoyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $reclamationVoyage->setIdVoy($voyage);
            $em->persist($reclamationVoyage);
            $em->flush();

            return $this->redirectToRoute('reclamationvoyage_show', array('idReclamationVoyage' => $reclamationVoyage->getIdreclamationvoyage()));
        }

        return $this->render('reclamationvoyage/new.html.twig', array(
            'reclamationVoyage' => $reclamationVoyage,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a reclamationVoyage entity.
     *
     */
    public function showAction(ReclamationVoyage $reclamationVoyage)
    {
        $deleteForm = $this->createDeleteForm($reclamationVoyage);

        return $this->render('reclamationvoyage/show.html.twig', array(
            'reclamationVoyage' => $reclamationVoyage,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing reclamationVoyage entity.
     *
     */
    public function editAction(Request $request, ReclamationVoyage $reclamationVoyage)
    {
        $deleteForm = $this->createDeleteForm($reclamationVoyage);
        $editForm = $this->createForm('VoyageBundle\Form\ReclamationVoyageType', $reclamationVoyage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reclamationvoyage_edit', array('idReclamationVoyage' => $reclamationVoyage->getIdreclamationvoyage()));
        }

        return $this->render('reclamationvoyage/edit.html.twig', array(
            'reclamationVoyage' => $reclamationVoyage,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a reclamationVoyage entity.
     *
     */
    public function deleteAction(Request $request, ReclamationVoyage $reclamationVoyage)
    {
        $form = $this->createDeleteForm($reclamationVoyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reclamationVoyage);
            $em->flush();
        }

        return $this->redirectToRoute('reclamationvoyage_index');
    }


    /**
     * Creates a form to delete a reclamationVoyage entity.
     *
     * @param ReclamationVoyage $reclamationVoyage The reclamationVoyage entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ReclamationVoyage $reclamationVoyage)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reclamationvoyage_delete', array('idReclamationVoyage' => $reclamationVoyage->getIdreclamationvoyage())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


}
