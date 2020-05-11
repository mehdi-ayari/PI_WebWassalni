<?php

namespace ReservationBundle\Controller;

use ReservationBundle\Entity\Reservation;
use ReservationBundle\Entity\ReservationBusiness;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
            $entreprise = $this->getUser()->getId();
            $en=$em->getRepository('AppBundle:User')->find($entreprise);
            $reservationBusiness->setUserEntreprise($en);
            $em->persist($reservationBusiness);
            $em->flush();

            return $this->redirectToRoute('reservationbusiness_show', array('idResBusiness' => $reservationBusiness->getIdresbusiness()));
        }

        return $this->render('@Reservation/reservationbusiness/new.html.twig', array(
            'reservationBusiness' => $reservationBusiness,
            'form' => $form->createView(),
        ));
    }

    public function allbusinessAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('ReservationBundle:ReservationBusiness')->findAll();
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($categories, 'json');
        echo $jsonContent;
        return new Response($jsonContent);
    }

    public function ajouterbusinessAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation=new ReservationBusiness();
        $user = $em->getRepository('AppBundle:User')->find(4);
        $reservation->setDestination($request->get('destination'));
        $reservation->setPointdepart($request->get('pointDepart'));
        $reservation->setDateReservation(new \DateTime('now'));
        $reservation->setPrenonClientEntreprise($request->get('prenonClientEntreprise'));
        $reservation->setNomClientEntreprise($request->get('nomClientEntreprise'));
        $reservation->setDateDepart($request->get('dateDepart'));
        $reservation->setUserEntreprise($user);

        $em->persist($reservation);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize($reservation);
        return new JsonResponse($formatted);
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
