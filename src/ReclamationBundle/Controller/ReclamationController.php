<?php

namespace ReclamationBundle\Controller;

use ReclamationBundle\Entity\Reclamation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReclamationController extends Controller
{

    public function AjoutReclamationAction( \Symfony\Component\HttpFoundation\Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = new Reclamation();
        $reclamation->setDateReclamation(new \DateTime('now'));
        $reclamation->setEtat("En Cours");
        $reclamation->setUser($this->getUser());
        $form = $this->createForm('ReclamationBundle\Form\ReclamationType', $reclamation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($reclamation);
            $em->flush();
             return $this->redirectToRoute('Afficher_Reclamation');
        }
        return $this->render('@Reclamation/Reclamation/AjouterReclamation.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function AfficheReclamtionAdminAction()
    {
        $m = $this->getDoctrine()->getManager();
        $count = $this->count();
        $reclamation = $m->getRepository("ReclamationBundle:Reclamation")->findAll();
        return $this->render('@Reclamation/Reclamation/AfficherAdminReclamation.html.twig', array(
            'rec' => $reclamation,
            'c'=>$count,

        ));
    }

    public function AfficheReclamtionUserAction()
    {
        $m = $this->getDoctrine()->getManager();
        $reclamation = $m->getRepository("ReclamationBundle:Reclamation")->findBy(array('user'=>$this->getUser()->getId()));
        return $this->render('@Reclamation/Reclamation/AfficherReclamation.html.twig', array(
            'rec' => $reclamation,
        ));
    }


    public function deleteReclamationAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $reclamations = $em->getRepository("ReclamationBundle:Reclamation")->find($id);
        $em->remove($reclamations);
        $em->flush();
        return $this->redirectToRoute('Afficher_Reclamation');

    }

    public function ModifierReclamtionAction(\Symfony\Component\HttpFoundation\Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $reclamation = $em->getRepository('ReclamationBundle:Reclamation')->find($id);
        $reclamation->setDateReclamation(new \DateTime('now'));
        $reclamation->setEtat("En Cours");
        $editForm = $this->createForm('ReclamationBundle\Form\ReclamationType', $reclamation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->persist($reclamation);
            $em->flush();

            return $this->redirectToRoute('Afficher_Reclamation');
        }

        return $this->render('@Reclamation/Reclamation/ModifierReclamation.html.twig', array(
            'form' => $editForm->createView(),
        ));
    }

    public function TraiteRecclamationAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $Reclamation = $em->getRepository("ReclamationBundle:Reclamation")->find($id);
        $Reclamation->setEtat('Traité');


            $message = \Swift_Message::newInstance()

                ->setSubject('Reclamtion Traite')
                ->setFrom('seifeddinejemai123@gmail.com')
                ->setTo($Reclamation->getUser()->getEmail())
                ->setBody(
                    $this->renderView(

                    // app/Resources/views/Emails/registration.html.twig
                        '@Reclamation/Email/MailBody.html.twig',
                        array(
                            'titre' => $Reclamation->getTitre(),
                            'date' => $Reclamation->getDateReclamation(),
                            'nom'=>$Reclamation->getUser()->getUsername(),
                        )


                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);

        $em->persist($Reclamation);
        $em->flush();
        return $this->redirectToRoute('AdminAfficher_Reclamation');

    }

    public function ArchiverReclamationAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $reclamations = $em->getRepository("ReclamationBundle:Reclamation")->find($id);
        $reclamations->setEtat('Archiver');
        $em->persist($reclamations);
        $em->flush();
        return $this->redirectToRoute('AdminAfficher_Reclamation');

    }

    public function AfficheReclamtionArchiverAction()
    {
        $m = $this->getDoctrine()->getManager();
        $reclamation = $m->getRepository("ReclamationBundle:Reclamation")->findAll();
        return $this->render('@Reclamation/Reclamation/ReclamtionArchiver.html.twig', array(
            'rec' => $reclamation,
        ));
    }

    public function count()
    {
        $count = 0;
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository("ReclamationBundle:Reclamation")->findBy(array('etat'=>"En Cours"));
        foreach ($commentaire as $e){
            $count = $count + 1;
        }

        return $count;

    }

    public function allrecAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        //$recs = $em->getRepository('ShopBundle:Reclamation')
        //->findAll();
        $recs = $em->getRepository('ReclamationBundle:Reclamation')
            ->findByUser($id);

        $data= array();
        foreach ($recs as $key =>$comm){
            $data[$key]['id']=$comm->getIdReclamation();
            $data[$key]['titre']=$comm->getTitre();
            $data[$key]['description']=$comm->getDescription();
            $data[$key]['etat']=$comm->getEtat();
            $data[$key]['date']=$comm->getDateReclamation();
            //->format('d/m/Y H:i');
            $data[$key]['user']=$comm->getUser()->getId();
            $data[$key]['type']=$comm->getTypeRec()->getType();


        }

        return new JsonResponse($data);
    }

    public function updateReclamationAction($id,$idt, $titre, $description)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reclamation = $entityManager->getRepository('ReclamationBundle:Reclamation')->find($id);
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('ReclamationBundle:Type')
            ->find($idt);
        if (!$reclamation) {
            throw $this->createNotFoundException(
                'No command found for id '.$id
            );
        }
        $reclamation->setTitre($titre);
        $reclamation->setDescription($description);
        $reclamation->setTypeRec($type);
        //$reclamation->setDate(new \DateTime('now'));
        $entityManager->flush();
        return new JsonResponse("success");
    }


    public function deleteReclamationByIdAction($id)
    {

        $sn = $this->getDoctrine()->getManager();
        $rec = $sn->getRepository('ReclamationBundle:Reclamation')->find($id);
        $sn->remove($rec);
        $sn->flush();

        return new JsonResponse("success");
    }

    public function addReclamationAction($idt,$idu, $titre, $description)
    {
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('ReclamationBundle:Type')
            ->find($idt);
        $user = $em->getRepository('AppBundle:User')
            ->find($idu);
        $reclamation = new Reclamation();
        $reclamation->setDateReclamation(new \DateTime('now'));
        $reclamation->setTitre($titre);
        $reclamation->setDescription($description);
        $reclamation->setEtat("En Cours");
        $reclamation->setUser($user);
        $reclamation->setTypeRec($type);
        //$reclamation->setUtilisateur($user);
        /*
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);*/
        //return new JsonResponse($formatted);
        $em->persist($reclamation);
        $em->flush();
        return new JsonResponse("success");
    }

    public function getTypesAction()
    {
        $em = $this->getDoctrine()->getManager();
        //$recs = $em->getRepository('ShopBundle:Reclamation')
        //->findAll();
        $types = $em->getRepository('ReclamationBundle:Type')
            ->findAll();

        $data= array();
        foreach ($types as $key =>$comm){
            $data[$key]['id']=$comm->getId();
            $data[$key]['type']=$comm->getType();
        }

        return new JsonResponse($data);
    }



}
