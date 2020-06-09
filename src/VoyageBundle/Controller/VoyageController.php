<?php



namespace VoyageBundle\Controller;

use Doctrine\ORM\NoResultException;
use ReservationBundle\Entity\Reservation;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Twilio\Rest\Client;
use VoyageBundle\Entity\Voyage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use AppBundle\Entity\User;


/**
 * Voyage controller.
 *
 */
class VoyageController extends Controller
{
    /**
     * Lists all voyage entities.
     *
     */
    public $chart;



    public function indexFrontAction()
    {

        $em = $this->getDoctrine()->getManager();
        $reservation =$em->getRepository('ReservationBundle:Reservation')->findAll();
        $voyagesD = $em->getRepository('VoyageBundle:Voyage')->myfindVoyageDone();
        $voyages = $em->getRepository('VoyageBundle:Voyage')->myfindVoyage();
        $users=$em->getRepository('AppBundle:User')->findAll();
        $idconnected = $this->getUser();




        return $this->render('voyage/indexFront.html.twig', array(
            'reservations'=>$reservation,
            'voyages' => $voyages,
            'voyagesD' => $voyagesD,
            'users'=>$users,
            'idconnected'=>$idconnected
        ));



    }


    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $voyages = $em->getRepository('VoyageBundle:Voyage')->findAll();
        $Res = $em->getRepository('ReservationBundle:Reservation')->findAll();



        return $this->render('voyage/index.html.twig', array(
            'voyages' => $voyages,
            'reservation'=>$Res,
        ));
    }


    public function mapAction(Voyage $voyage)
    {

        if(isset($_GET['distance'])){
        $voyage->setDistance($_GET['distance']);
        $this->getDoctrine()->getManager()->flush();}


        return $this->render('voyage/map.html.twig',array('voyage' => $voyage)
        );
    }

    public function newAction()
    {


        try {
            $sender = "+14157544698";
            $account_sid='AC16fce352cd301d805ce075ba4b7969d0';
            $account_token='90023e188332683906bd451a7a227703';
            $client = new Client($account_sid,$account_token);
            $entityManager = $this->getDoctrine()->getManager();

            $voyages = $this->getDoctrine()->getManager()->getRepository(Voyage::class)
                ->myfinddate();
            echo count($voyages);
            if(count($voyages) > 0) {
                foreach ($voyages as $voy){
                    $voyage = new Voyage();
                    $Res = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->find($voy['idRes']);
                    $User = $this->getDoctrine()->getManager()->getRepository(User::class)->find($voy['idUser']);
                    $des = $Res->getDestination();
                    $voyage->setReservationRes($Res);

                    $voyage->setDistance(null);
                    $voyage->setDateVoyage($voy['dateReservation']);
                    $voyage->setAnnul(null);
                    $entityManager->persist($voyage);
                    $entityManager->flush();
                    $tel = $User->getTelephone();
                    /*   $client->messages->create(
                       // Where to send a text message (your cell phone?)
                           '+216'.$tel,
                           array(
                               'from' => $sender,
                               'body' => 'You have a new trip!'
                           )
                       );*/
                }}

            return $this->render('voyage/new.html.twig', array(
                'voyage' => $voyage,
                'reservation'=>$Res
            ));
        } catch (NoResultException $e){
        }




        return $this->render('voyage/new.html.twig', array(
            'voyage' => $voyage,
            'reservation'=>$Res
        ));
    }

    /**
     * Finds and displays a voyage entity.
     *
     */
    public function showAction(Voyage $voyage)
    {
        $deleteForm = $this->createDeleteForm($voyage);

        return $this->render('voyage/show.html.twig', array(
            'voyage' => $voyage,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing voyage entity.
     *
     */
    public function editAction(Request $request, Voyage $voyage)
    {
        $deleteForm = $this->createDeleteForm($voyage);
        $editForm = $this->createForm('VoyageBundle\Form\VoyageType', $voyage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('voyage_edit', array('idVoyage' => $voyage->getIdvoyage()));
        }

        return $this->render('voyage/edit.html.twig', array(
            'voyage' => $voyage,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function deleteAction(Request $request, Voyage $voyage)
    {
        $form = $this->createDeleteForm($voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($voyage);
            $em->flush();
        }
        if($this->getUser()->hasRole('ROLE_ADMIN')){
        return $this->redirectToRoute('voyage_index');}
        else{
            return $this->redirectToRoute('voyage_indexFront');}
    }


    /**
     * Creates a form to delete a reclamationVoyage entity.
     *
     * @param Voyage $voyage The reclamationVoyage entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Voyage $voyage)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('voyage_delete', array('idVoyage' => $voyage->getIdvoyage())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    public function allAction()
    {
        $voyages = $this->getDoctrine()->getManager()
            ->getRepository('VoyageBundle:Voyage')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($voyages);
        return new JsonResponse($formatted);
    }

    public function findAction()
    {
        $em = $this->getDoctrine()->getManager();
        $voyages = $em->getRepository('VoyageBundle:Voyage')->myfindVoyage();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($voyages);
        return new JsonResponse($formatted);
    }

    public function findDoneAction()
    {
        $em = $this->getDoctrine()->getManager();
        $voyagesD = $em->getRepository('VoyageBundle:Voyage')->myfindVoyageDone();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($voyagesD);
        return new JsonResponse($formatted);

    }

    public function loginMobileAction($username, $password)
    {
        $user_manager = $this->get('fos_user.user_manager');
        $factory = $this->get('security.encoder_factory');
        $data = [
            'type' => 'validation error',
            'title' => 'There was a validation error',
            'errors' => 'username or password invalide'
        ];
        $response = new JsonResponse($data, 400);
        $user = $user_manager->findUserByUsername($username);
        if (!$user)
            return $response;
        $encoder = $factory->getEncoder($user);
        $bool = ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) ? "true" : "false";
        if ($bool == "true") {
            $role = $user->getRoles();
            $data = array('type' => 'Login succeed',
                'id' => $user->getId(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'email' => $user->getEmail(),
                'image' => $user->getImage(),
                'birthDay' => $user->getBirthDay()->format('d-m-Y'),
                'role' => $user->getRoles(),
                'gender' => $user->getGender());
            $response = new JsonResponse($data, 200);
            return $response;
        } else {
            return $response;
        }
        // return array('name' => $bool);
    }





}
