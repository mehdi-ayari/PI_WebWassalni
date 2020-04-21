<?php


namespace ReservationBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ReservationBundle\Entity\Reservation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function afficherlistereservationsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $reservations = $em->getRepository('ReservationBundle:Reservation')->findAll();
        $dql= "SELECT bp FROM ReservationBundle:Reservation bp ";
        $query = $em->CreateQuery($dql);
        /**
         * @var $paginator Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result= $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',5)
        );
        return $this->render('@Reservation/back/listereservations.html.twig', array(
            'reservations' => $result
        ));
    }

    public function afficherlisteusersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();
        $dql= "SELECT bp FROM AppBundle:User bp ";
        $query = $em->CreateQuery($dql);
        /**
         * @var $paginator Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result= $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',5)
        );
        return $this->render('@Reservation/back/listeusers.html.twig', array(
            'users' => $result
        ));
    }

    public function afficherlistebusinessAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $reservations = $em->getRepository('ReservationBundle:ReservationBusiness')->findAll();
        $dql= "SELECT bp FROM ReservationBundle:ReservationBusiness bp ";
        $query = $em->CreateQuery($dql);
        /**
         * @var $paginator Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result= $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',5)
        );
        return $this->render('@Reservation/back/listebusiness.html.twig', array(
            'reservations' => $result
        ));
    }

    public function traitementAction($idResBusiness)
    {
        $em = $this->getDoctrine()->getManager();
        $res=new Reservation();
        $form = $this->createForm('ReservationBundle\Form\ReservationType', $res);
        $users = $em->getRepository('AppBundle:User')->findAll();
        $reservations = $em->getRepository('ReservationBundle:ReservationBusiness')->find($idResBusiness);
        $date=$reservations->getDateReservation();
        $string=$date->format("D, d M Y H:i:s O");
        $date1=$reservations->getDateDepart();
        $string2=$date1->format("D, d M Y H:i:s O");
        return $this->render('@Reservation/back/traiterbusiness.html.twig', array(
            'reservation' => $reservations,
            'users' => $users,
            'string'=>$string,
            'string2'=>$string2,
            'form'=>$form->createView()
        ));

    }


    public function convertirAction($idResBusiness,$prix,$chauffeur)
    {
        $snappy = $this->get('knp_snappy.pdf');
        $em = $this->getDoctrine()->getManager();
        $res=new Reservation();
        $form = $this->createForm('ReservationBundle\Form\ReservationType', $res);
        $users = $em->getRepository('AppBundle:User')->findAll();
        $reservations = $em->getRepository('ReservationBundle:ReservationBusiness')->find($idResBusiness);
        $qb=$em->createQuery('Update ReservationBundle:ReservationBusiness r SET r.etat=1 where r.idResBusiness=:id')
            ->setParameter('id',$idResBusiness)->execute();
        $date=$reservations->getDateReservation();
        $string=$date->format("D, d M Y H:i:s O");
        $date1=$reservations->getDateDepart();
        $string2=$date1->format("D, d M Y H:i:s O");
        $html = $this->render('@Reservation/back/convertirpdf.html.twig', array(
            'reservation' => $reservations,
            'users' => $users,
            'string'=>$string,
            'string2'=>$string2,
            'prix'=>$prix,
            'chauffeur'=>$chauffeur,
            'form'=>$form->createView()
        ));

        $filename = 'Traitement';
        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '.pdf"'
            )
        );

    }

    public function calendrierAction()
    {
        return $this->render('@Reservation/back/calendar.html.twig');
    }



}