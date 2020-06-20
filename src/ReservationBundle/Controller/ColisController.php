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
        $em = $this->getDoctrine()->getManager();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $dest=$editForm1->get('destination')->getData();
            $depart=$editForm1->get('pointdepart')->getData();
            $type=$editForm1->get('typeReservation')->getData();
            $date=new \DateTime('now');
            $string=$date->format("D, d M Y H:i:s O");
            $date = explode(" ", $string);
            $latlong=$this->lat_longAction($dest);
            $latlong1=$this->lat_longAction($depart);
            $lat2=$latlong1['lat'];
            $lon2=$latlong1['lon'];
            $lat1=$latlong['lat'];
            $lon1=$latlong['lon'];
            $distance=$this->distanceAction($lat1,$lon1,$lat2,$lon2,'K');
            $distance=$distance*1.3;
            $prix=$this->calcul_prix_Action($type,$distance,$date[4]);
            $reservation->setPrix($prix);
            $em->persist($coli);
            $em->flush();
            $id=$coli->getIdColis();
            $c=$em->getRepository('ReservationBundle:Colis')->find($id);
            $reservation->setIdColis($c);
            $em->persist($reservation);
            $em->flush();

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


    function lat_longAction($address)
    {
        if($address=="centre ville,Tunis")
        {
            $lat=36.8004904;
            $lon=10.185332118993045;
            $array = array('lat'=> $lat ,'lon'=>$lon);
            return $array;
        }
        if($address=="Ariana")
        {
            $lat=36.9685735;
            $lon=10.1219855;
            $array = array('lat'=> $lat ,'lon'=>$lon);
            return $array;
        }
        if($address=="Ben arous")
        {
            $lat=36.7718;
            $lon=10.2386203;
            $array = array('lat'=> $lat ,'lon'=>$lon);
            return $array;
        }
        if($address=="Bardo")
        {
            $lat=36.8134113;
            $lon=10.13219109;
            $array = array('lat'=> $lat ,'lon'=>$lon);
            return $array;
        }
        if($address=="Carthage")
        {
            $lat=36.8577565;
            $lon=10.32821822;
            $array = array('lat'=> $lat ,'lon'=>$lon);
            return $array;
        }
        if($address=="Mourouj")
        {
            $lat=36.719825;
            $lon=10.21923624;
            $array = array('lat'=> $lat ,'lon'=>$lon);
            return $array;
        }
        if($address=="Megrine")
        {
            $lat=36.77179995;
            $lon=10.23862035;
            $array = array('lat'=> $lat ,'lon'=>$lon);
            return $array;
        }

    }

    function distanceAction($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }

    function calcul_prix_Action($type,$distance,$date)
    {

        if ($type=="Taxi" && $date<"21:00:00" )
        {
            return 450+(400*$distance);
        }
        if ($type=="Taxi" && $date>"21:00:00" )
        {
            return 700+(500*$distance);
        }
        if ($type=="Privée" && $date<"21:00:00")
        {
            return 1000+(600*$distance);
        }
        if ($type=="Privée" && $date>"21:00:00")
        {
            return 1500+(650*$distance);
        }
        if ($type=="camion" && $date>"21:00:00")
        {
            return 4500+(2100*$distance);
        }
        if ($type=="camion" && $date<"21:00:00")
        {
            return 3500+(1800*$distance);
        }
    }


}
