<?php

namespace ReservationBundle\Controller;

use ReservationBundle\Entity\Colis;
use ReservationBundle\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Coli controller.
 *
 * @Route("colis")
 */
class ColisController extends Controller
{
    /**
     * Lists all coli entities.
     *
     * @Route("/", name="colis_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $colis = $em->getRepository('ReservationBundle:Colis')->findAll();
        $reservation=$em->getRepository('ReservationBundle:Reservation')->findAll();
        $idconnected = $this->getUser()->getId();
        $users = $em->getRepository('AppBundle:User')->findAll();
        return $this->render('@Reservation/reservation/index.html.twig', array(
            'colis' => $colis,
            'reservations'=>$reservation,
            'idconnected'=>$idconnected,
            'users'=>$users
        ));
    }

    /**
     * Creates a new coli entity.
     *
     * @Route("/new", name="colis_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $coli = new Colis();
        $form = $this->createForm('ReservationBundle\Form\ColisType', $coli);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($coli);
            $em->flush();

            return $this->redirectToRoute('colis_show', array('idColis' => $coli->getIdcolis()));
        }

        return $this->render('@Reservation/colis/new.html.twig', array(
            'coli' => $coli,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a coli entity.
     *
     * @Route("/{idColis}", name="colis_show")
     * @Method("GET")
     */
    public function showAction(Reservation $reservation,Colis $coli)
    {
        $deleteForm = $this->createDeleteForm($coli);

        return $this->render('@Reservation/reservation/show.html.twig', array(
            'coli' => $coli,
            'reservation'=>$reservation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing coli entity.
     *
     * @Route("/{idColis}/edit", name="colis_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request,Request $request1, Colis $coli,Reservation $reservation)
    {
        $deleteForm = $this->createDelete2Form($coli,$reservation);
        $editForm = $this->createForm('ReservationBundle\Form\ColisType', $coli);
        $editForm->handleRequest($request);
        $editForm1=$this->createForm('ReservationBundle\Form\ReservationType',$reservation);
        $editForm1->handleRequest($request1);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('colis_edit', array('idColis' => $coli->getIdcolis(),'id'=>$reservation->getId()));
        }

        return $this->render('@Reservation/reservation/edit.html.twig', array(
            'coli' => $coli,
            'reservation'=>$reservation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'edit_form1' => $editForm1->createView(),
        ));
    }

    /**
     * Deletes a coli entity.
     *
     * @Route("/{idColis}/delete", name="colis_delete")
     * @Method("DELETE")
     */
    public function deleteAction($idColis)
    {
        $r= $this->getDoctrine()->getRepository(Reservation::class)->find($idColis);
        $c=$r->getIdColis();

        $em=$this->getDoctrine()->getManager();

        $em->remove($r);
        $em->remove($c);
        $em->flush();


        return $this->redirectToRoute('colis_index');
    }

    /**
     * Creates a form to delete a coli entity.
     *
     * @param Colis $coli The coli entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDelete2Form(Colis $coli,Reservation $reservation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('colis_delete', array('idColis' => $coli->getIdcolis())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    /**
     * Creates a form to delete a coli entity.
     *
     * @param Colis $coli The coli entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Colis $coli)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('colis_delete', array('idColis' => $coli->getIdcolis())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    public function deleteColisAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $club=$em->getRepository('ReservationBundle:Colis')->find($id);
        var_dump($club);
        $em=$this->getDoctrine()->getManager();
        $em->remove($club);
        $em->flush();
        return new Response( "bonjouur");
    }


}
