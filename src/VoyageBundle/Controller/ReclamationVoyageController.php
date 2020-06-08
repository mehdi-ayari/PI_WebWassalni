<?php

namespace VoyageBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use VoyageBundle\Entity\ReclamationVoyage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use VoyageBundle\Entity\Voyage;
use AppBundle\Entity\User;
use ReservationBundle\Entity\Reservation;



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

    public function indexFrontAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reclamationVoyages = $em->getRepository('VoyageBundle:ReclamationVoyage')->findAll();
        $reservation =$em->getRepository('ReservationBundle:Reservation')->findAll();
        $voyages = $em->getRepository('VoyageBundle:Voyage')->findAll();
        $idconnected = $this->getUser();
        $users=$em->getRepository('AppBundle:User')->findAll();





        return $this->render('reclamationvoyage/indexFront.html.twig', array(
            'reclamationVoyages' => $reclamationVoyages,
            'reservations' => $reservation,
            'voyages' => $voyages,
            'idconnected' => $idconnected,
            'users' => $users,

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

            return $this->redirectToRoute('reclamationvoyage_indexFront');
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

        if($this->getUser()->hasRole('ROLE_ADMIN')){
            return $this->redirectToRoute('voyage_index');}
        else{
            return $this->redirectToRoute('voyage_indexFront');}}


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

    public function addAction(Request $request,Voyage $voyage){
        $em = $this->getDoctrine()->getManager();
        $RecVoy = new ReclamationVoyage();
        $RecVoy->setCommentaire($request->get('commentaire'));
        $RecVoy->setTitre($request->get('titre'));
        $RecVoy->setIdVoy($voyage);
        $em->persist($RecVoy);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($RecVoy);
        return new JsonResponse($formatted);
        }

        public function allAction()
        {
        $RecVoy = $this->getDoctrine()->getManager()->getRepository('VoyageBundle:ReclamationVoyage')->findAll();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($RecVoy);
            return new JsonResponse($formatted);

        }

        public function findAction($idReclamationVoyage){
            $RecVoy = $this->getDoctrine()->getManager()->getRepository('VoyageBundle:ReclamationVoyage')->find($idReclamationVoyage);
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($RecVoy);
            return new JsonResponse($formatted);

        }



    public function modifAction(Request $request, $idReclamationVoyage)
    {
        $RecVoy = $this->getDoctrine()->getManager()->getRepository('VoyageBundle:ReclamationVoyage')->find($idReclamationVoyage);

        $RecVoy->setTitre($request->get('titre'));

        $RecVoy->setCommentaire($request->get('commentaire'));

        $em1 = $this->getDoctrine()->getManager();
        $em1->persist($RecVoy);
        $em1->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($RecVoy);
        return new JsonResponse($formatted);
    }

    public function delAction($idReclamationVoyage)
    {
        $RecVoy = $this->getDoctrine()->getManager()->getRepository('VoyageBundle:ReclamationVoyage')->find($idReclamationVoyage);

        $this->getDoctrine()->getManager()->getRepository('VoyageBundle:ReclamationVoyage')->removeRecVoy($RecVoy);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($RecVoy);
        return new JsonResponse($formatted);
    }



}
