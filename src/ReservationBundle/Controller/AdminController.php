<?php


namespace ReservationBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ReservationBundle\Entity\Reservation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;


class AdminController extends Controller
{
    public function afficherlistereservationsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reservations = $em->getRepository('ReservationBundle:Reservation')->findAll();
        return $this->render('@Reservation/back/listereservations.html.twig', array(
            'reservations' => $reservations
        ));
    }

    public function afficherlisteusersAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();
        return $this->render('@Reservation/back/listeusers.html.twig', array(
            'users' => $users
        ));
    }

    public function afficherlistebusinessAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reservations = $em->getRepository('ReservationBundle:ReservationBusiness')->findAll();
        return $this->render('@Reservation/back/listebusiness.html.twig', array(
            'reservations' => $reservations
        ));
    }
}