<?php


namespace NewsBundle\Controller;


use NewsBundle\Entity\Comment;
use NewsBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{


    public function ajoutercommentAction(Request $request)
    {
        $Comment = new Comment();
        $form = $this->createForm(CommentType::class,$Comment);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $Comment->setUser($user);
            $Comment->setCreatedAt(new \DateTime());
            $em =$this->getDoctrine()->getManager();
            $em->persist($Comment);
            $em->flush();
            return $this->redirectToRoute("ajoutercomment");

        }
        return $this->render("@News/news/comment.html.twig",array('form'=>$form->createView()));


    }
    public function deleteAction(Request $request, Comment $comment)
    {

        if($comment){
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
        }

        $request->getSession()
            ->getFlashBag()
            ->add('notice', 'success');
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);


    }



}
