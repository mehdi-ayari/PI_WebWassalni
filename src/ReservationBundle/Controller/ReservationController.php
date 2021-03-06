<?php

namespace ReservationBundle\Controller;

use AppBundle\Entity\User;
use http\Client;
use ReservationBundle\Entity\Colis;
use ReservationBundle\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ReservationBundle\Entity\ReservationBusiness;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;


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
    public function indexAction(Request $request)
    {
        $objet="passager";
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $reservations = $em->getRepository('ReservationBundle:Reservation')->findBy(array('userClient'=>$user,'objet'=>$objet));
        $dql= "SELECT bp FROM ReservationBundle:Reservation bp WHERE bp.userClient =?0 and bp.objet=?1";
        $query=$em->createQuery($dql)->setParameter(0,$user)->setParameter(1,$objet) ;
       // $query = $em->CreateQuery($dql);


        /**
         * @var $paginator Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result= $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',5)
        );
        return $this->render('@Reservation/reservation/indexpassager.html.twig', array(
            'reservations' => $result,
        ));
    }

    public function allAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository('ReservationBundle:Reservation')->findAll();
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($reservations, 'json');
        echo $jsonContent;
        return new Response($jsonContent);
    }

    public function supprimerjsonAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $res = $em->getRepository('ReservationBundle:Reservation')->find($request->get('id'));
        $em->remove($res);

        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize($res);
        return new JsonResponse($formatted);
    }

    public function ajouterAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation=new Reservation();
        $colis=new Colis();
        $user = $em->getRepository('AppBundle:User')->find(1);
        $chauffeur=$em->getRepository('AppBundle:User')->find($request->get('userChauffeur'));
        $reservation->setDestination($request->get('destination'));
        $reservation->setPointdepart($request->get('pointdepart'));
        $reservation->setDateReservation(new \DateTime('now'));
        $reservation->setTypeReservation($request->get('typeReservation'));
        $reservation->setPrix($request->get('prix'));
        $reservation->setObjet($request->get('objet'));
        $reservation->setUserClient($user);
        $reservation->setUserChauffeur($chauffeur);
        $objet=$request->get('objet');
        if ($objet=="colis")
        {
            $colis->setContenu($request->get('contenu'));
            $colis->setPoids($request->get('poids'));
            $em->persist($colis);
            $em->flush();
            $id=$colis->getIdColis();
            $c=$em->getRepository('ReservationBundle:Colis')->find($id);
            $reservation->setIdColis($c);
        }
        $em->persist($reservation);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize($reservation);
        return new JsonResponse($formatted);
    }

    public function supprimerAction($id)
    {
        $form = $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $em=$this->getDoctrine()->getManager();

        $em->remove($form);

        $em->flush();

        return $this->redirectToRoute('reservation_index');
    }


    public function modifierAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('ReservationBundle:Reservation')->find($request->get('id'));
        $user = $em->getRepository('AppBundle:User')->find(1);
        $reservation->setUserClient($user);

        if ($request->get('destination')!=null ){
            $reservation->setDestination($request->get('destination'));}

        if($request->get('pointdepart') !=null){
            $reservation->setPointdepart($request->get('pointdepart'));

        }

        if($request->get('typeReservation') !=null){
            $reservation->setTypeReservation($request->get('typeReservation'));

        }
        if($request->get('objet') !=null){
            $reservation->setObjet($request->get('objet'));

        }
        if($request->get('userChauffeur') !=null){
            $chauffeur=$em->getRepository('AppBundle:User')->find($request->get('userChauffeur'));
            $reservation->setUserChauffeur($chauffeur);

        }
        if($request->get('prix') !=null){
            $reservation->setPrix($request->get('prix'));

        }





        $em->persist($reservation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reservation);
        return new JsonResponse($formatted);

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
        $em = $this->getDoctrine()->getManager();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $type=$editForm->get('typeReservation')->getData();
            $dest=$editForm->get('destination')->getData();
            $depart=$editForm->get('pointdepart')->getData();
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
            $em->persist($reservation);
            $em->flush();

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
                $dest=$form->get('destination')->getData();
                $depart=$form->get('pointdepart')->getData();
                $type=$form->get('typeReservation')->getData();
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
                    $reservationBusiness->setEtat(0);
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
                $dest=$form->get('destination')->getData();
                $depart=$form->get('pointdepart')->getData();
                $type=$form->get('typeReservation')->getData();
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
                $reservationBusiness->setEtat(0);
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
