<?php

namespace ReservationBundle\Controller;

use ReservationBundle\Entity\ReservationBusiness;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Reservationbusiness controller.
 *
 * @Route("reservationbusiness")
 */
class ReservationBusinessController extends Controller
{
    /**
     * Lists all reservationBusiness entities.
     *
     * @Route("/", name="reservationbusiness_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reservationBusinesses = $em->getRepository('ReservationBundle:ReservationBusiness')->findAll();
        $idconnected = $this->getUser()->getId();
        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('@Reservation/reservationbusiness/index.html.twig', array(
            'reservationBusinesses' => $reservationBusinesses, 'idconnected'=>$idconnected, 'users'=>$users
        ));
    }

    /**
     * Creates a new reservationBusiness entity.
     *
     * @Route("/new", name="reservationbusiness_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $reservationBusiness = new Reservationbusiness();
        $form = $this->createForm('ReservationBundle\Form\ReservationBusinessType', $reservationBusiness);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservationBusiness);
            $em->flush();

            return $this->redirectToRoute('reservationbusiness_show', array('idResBusiness' => $reservationBusiness->getIdresbusiness()));
        }

        return $this->render('@Reservation/reservationbusiness/new.html.twig', array(
            'reservationBusiness' => $reservationBusiness,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a reservationBusiness entity.
     *
     * @Route("/{idResBusiness}", name="reservationbusiness_show")
     * @Method("GET")
     */
    public function showAction(ReservationBusiness $reservationBusiness)
    {
        $deleteForm = $this->createDeleteForm($reservationBusiness);

        return $this->render('@Reservation/reservationbusiness/show.html.twig', array(
            'reservationBusiness' => $reservationBusiness,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing reservationBusiness entity.
     *
     * @Route("/{idResBusiness}/edit", name="reservationbusiness_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ReservationBusiness $reservationBusiness)
    {
        $deleteForm = $this->createDeleteForm($reservationBusiness);
        $editForm = $this->createForm('ReservationBundle\Form\ReservationBusinessType', $reservationBusiness);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reservationbusiness_edit', array('idResBusiness' => $reservationBusiness->getIdresbusiness()));
        }

        return $this->render('@Reservation/reservationbusiness/edit.html.twig', array(
            'reservationBusiness' => $reservationBusiness,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a reservationBusiness entity.
     *
     * @Route("/{idResBusiness}", name="reservationbusiness_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ReservationBusiness $reservationBusiness)
    {
        $form = $this->createDeleteForm($reservationBusiness);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reservationBusiness);
            $em->flush();
        }

        return $this->redirectToRoute('reservationbusiness_index');
    }

    /**
     * Creates a form to delete a reservationBusiness entity.
     *
     * @param ReservationBusiness $reservationBusiness The reservationBusiness entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ReservationBusiness $reservationBusiness)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reservationbusiness_delete', array('idResBusiness' => $reservationBusiness->getIdresbusiness())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
