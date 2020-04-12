<?php

namespace ReservationBundle\Controller;

use AppBundle\Entity\User;
use http\Client;
use ReservationBundle\Entity\Colis;
use ReservationBundle\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ReservationBundle\Entity\ReservationBusiness;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Security\Core\Security;


/**
 * Reservation controller.
 *
 * @Route("reservation")
 */
class ReservationController extends Controller
{
    /**
     * Lists all reservation entities.
     *
     * @Route("/", name="reservation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reservations = $em->getRepository('ReservationBundle:Reservation')->findAll();
        $idconnected = $this->getUser()->getId();
        $users = $em->getRepository('AppBundle:User')->findAll();
        return $this->render('@Reservation/reservation/indexpassager.html.twig', array(
            'reservations' => $reservations, 'idconnected'=>$idconnected, 'users'=>$users
        ));
    }

    /**
     * Creates a new reservation entity.
     *
     * @Route("/new", name="reservation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $reservation =new Reservation();
        $form = $this->createForm('ReservationBundle\Form\ReservationType', $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $reservation->setUserClient($this->getUser());
            $em->persist($reservation);
            $em->flush();

            return $this->redirectToRoute('reservation_show', array('id' => $reservation->getId()));
        }

        return $this->render('@Reservation/reservation/newpassager.html.twig', array(
            'reservation' => $reservation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a reservation entity.
     *
     * @Route("/{id}", name="reservation_show")
     * @Method("GET")
     */
    public function showAction(Reservation $reservation)
    {
        $deleteForm = $this->createDeleteForm($reservation);

        return $this->render('@Reservation/reservation/showpassager.html.twig', array(
            'reservation' => $reservation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing reservation entity.
     *
     * @Route("/{id}/edit", name="reservation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Reservation $reservation)
    {
        $deleteForm = $this->createDeleteForm($reservation);
        $editForm = $this->createForm('ReservationBundle\Form\ReservationType', $reservation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reservation_edit', array('id' => $reservation->getId()));
        }

        return $this->render('@Reservation/reservation/editpassager.html.twig', array(
            'reservation' => $reservation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a reservation entity.
     *
     * @Route("/{id}/delete", name="reservation_delete")
     * @Method("DELETE")
     */
    public function deleteAction($id)
    {
        $form = $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $em=$this->getDoctrine()->getManager();

        $em->remove($form);

        $em->flush();

        return $this->redirectToRoute('reservation_index');
    }

    /**
     * Creates a form to delete a reservation entity.
     *
     * @param Reservation $reservation The reservation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Reservation $reservation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reservation_delete', array('id' => $reservation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function enum()
    {
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    public function bnAction()
    {
        return new Response("Bonjour");
    }

    public function rootAction(Request $request, Request $request1)
    {
        $clientconnected = $this->container->get('security.authorization_checker')->isGranted('ROLE_CLIENT');
        $entrepriseconnected = $this->container->get('security.authorization_checker')->isGranted('ROLE_ENTREPRISE');


        if ($clientconnected == true)
        {
            $reservation =new Reservation();
            $colis= new Colis();
            $form = $this->createForm('ReservationBundle\Form\ReservationType', $reservation);
            $form->handleRequest($request);
            $form1 = $this->createForm('ReservationBundle\Form\ColisType', $colis);
            $form1->handleRequest($request1);



            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $reservation->setUserClient($this->getUser());
                $reservation->setObjet("colis");
                $em->persist($colis);
                $em->flush();
                $id=$colis->getIdColis();
                $c=$em->getRepository('ReservationBundle:Colis')->find($id);
                $reservation->setIdColis($c);
                $em->persist($reservation);
                $em->flush();


                return $this->redirectToRoute('colis_show', array('id' => $reservation->getId(),'idColis'=>$colis->getIdColis()));
            }


            return $this->render('@Reservation/reservation/new.html.twig', array(
                'reservation' => $reservation,
                'form' => $form->createView(),
                'colis' => $colis,
                'form1' => $form1->createView(),

            ));
        }

        elseif ($entrepriseconnected == true)
            {
                $reservationBusiness = new Reservationbusiness();
                $form = $this->createForm('ReservationBundle\Form\ReservationBusinessType', $reservationBusiness);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $reservationBusiness->setUserEntreprise($this->getUser());
                    $em->persist($reservationBusiness);
                    $em->flush();

                    return $this->redirectToRoute('reservationbusiness_show', array('idResBusiness' => $reservationBusiness->getIdresbusiness()));
                }

                return $this->render('@Reservation/reservationbusiness/new.html.twig', array(
                    'reservationBusiness' => $reservationBusiness,
                    'form' => $form->createView(),
                ));
            }
        return $this->render('base.html.twig');

    }

    public function rootpassagerAction(Request $request)
    {
        $clientconnected = $this->container->get('security.authorization_checker')->isGranted('ROLE_CLIENT');
        $entrepriseconnected = $this->container->get('security.authorization_checker')->isGranted('ROLE_ENTREPRISE');


        if ($clientconnected == true)
        {
            $reservation =new Reservation();

            $form = $this->createForm('ReservationBundle\Form\ReservationType', $reservation);
            $form->handleRequest($request);




            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $reservation->setUserClient($this->getUser());
                $reservation->setObjet("passager");
                $em->persist($reservation);
                $em->flush();


                return $this->redirectToRoute('reservation_show', array('id' => $reservation->getId()));
            }


            return $this->render('@Reservation/reservation/newpassager.html.twig', array(
                'reservation' => $reservation,
                'form' => $form->createView(),


            ));
        }

        elseif ($entrepriseconnected == true)
        {
            $reservationBusiness = new Reservationbusiness();
            $form = $this->createForm('ReservationBundle\Form\ReservationBusinessType', $reservationBusiness);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $reservationBusiness->setUserEntreprise($this->getUser());
                $em->persist($reservationBusiness);
                $em->flush();

                return $this->redirectToRoute('reservationbusiness_show', array('idResBusiness' => $reservationBusiness->getIdresbusiness()));
            }

            return $this->render('@Reservation/reservationbusiness/new.html.twig', array(
                'reservationBusiness' => $reservationBusiness,
                'form' => $form->createView(),
            ));
        }
        return $this->render('base.html.twig');

    }



}
