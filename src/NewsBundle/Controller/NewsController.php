<?php

namespace NewsBundle\Controller;

use AppBundle\Entity\User;

use NewsBundle\Entity\News;
use NewsBundle\Form\NewsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\DependencyInjection\ContainerInterface;


use NewsBundle\Entity\Comment;
use NewsBundle\Form\CommentType;

class NewsController extends Controller
{
    public function ajouterNewsAction(Request $request)
    {
        $News = new News();
        $form = $this->createForm(NewsType::class,$News);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em =$this->getDoctrine()->getManager();
            /** @var UploadedFile $file */
            $file = $News->getImage();
            $filename= md5(uniqid(rand(), true))  . '.' . $file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $filename);
            $News->setImage($filename);
            $News->setViews(1);
            $em->persist($News);
            $em->flush();
            return $this->redirectToRoute('afficherNews');
        }
        return $this->render("@News/news/ajoutNews.html.twig",array('form'=>$form->createView()));
    }

    public function afficherNewsAction(request $request)
    {
        $em =$this->getDoctrine()->getManager();
        $News = $em->getRepository("NewsBundle:News")->findAll();
        $paginator  = $this->get('knp_paginator');

        $result = $paginator->paginate(
            $News,
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 3)/*limit per page*/
        );

        return $this->render('NewsBundle:news:afficherNews.html.twig', [
            'News' => $result,
        ]);

    }
    public function statistiqueAction(){
        $pieChart = new PieChart();
        $em= $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT c  ,UPPER(n.titre) as titre ,COUNT(c.id) as num FROM NewsBundle:Comment c
        join NewsBundle:News n with n.id=c.news GROUP BY c.news');
        $reservations=$query->getScalarResult();
        $data= array();
        $stat=['news', 'id'];
        $i=0;
        array_push($data,$stat);

        $ln= count($reservations);
        for ($i=0 ;$i<count($reservations);$i++){
            $stat=array();
            array_push($stat,$reservations[$i]['titre'],$reservations[$i]['num']/$ln);
            $stat=[$reservations[$i]['titre'],$reservations[$i]['num']*100/$ln];

            array_push($data,$stat);
        }
        $pieChart->getData()->setArrayToDataTable( $data );
        $pieChart->getOptions()->setTitle('NEWS Les plus commentes!');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        return $this->render('@News\news\statcom.html.twig', array('piechart' => $pieChart));
    }
    public function calendarAction()
    {

        $em = $this->getDoctrine()->getManager();
        $conn = $this->getDoctrine()->getEntityManager()->getConnection();
        $sql = "SELECT id, title, start, end, color FROM events  ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $this->getDoctrine()->getManager()->flush();
        $result=  $stmt->fetchAll();
        return $this->render('@News/news/calendar.html.twig',array('Events'=>$result));
// chnou theb tamel ? list news aleeh ?
    }
    public function consulterAction(Request $request, $id)
    {
        $comment = new Comment();
        $em =$this->getDoctrine()->getManager();
        $News = $em->getRepository("NewsBundle:News")->find($id);
        $form = $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $comment->setUser($user);
            $comment->setNews($News);
            $comment->setCreatedAt(new \DateTime());
            $em =$this->getDoctrine()->getManager();

            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute("consulter", array('id' => $News->getId()));

        }


        $emm =$this->getDoctrine()->getManager();

        $Comment = $emm->getRepository("NewsBundle:Comment")->ShowCmtBlog($id);


        return $this->render("@News/news/consulterback.html.twig",array(
            'form'=>$form->createView(),
            'News'=>$News,
            'Comment'=>$Comment));

    }

    /**
     * Reads an existing article
     *
     * @Route("/read/{id}", name="read_article")
     * @ParamConverter("news", options={"mapping": {"id": "id"}})
     * @Method({"GET", "POST"})
     */
    public function readAction(Request $request, News $news)
    {

        // Viewcounter
        $views = $this->get('tchoulom.view_counter')->getViews($news);
        $viewcounter = $this->get('tchoulom.view_counter')->getViewCounter($news);
        $viewcounter = (null != $viewcounter ? $viewcounter : new ViewCounter());

        $em = $this->getDoctrine()->getEntityManager();

        if ($this->get('tchoulom.view_counter')->isNewView($viewcounter)) {
            $viewcounter->setIp($request->getClientIp());
            $viewcounter->setNews($news);
            $viewcounter->setViewDate(new \DateTime('now'));

            $news->setViews($views);

            $em->persist($viewcounter);
            $em->persist($news);
            $em->flush();
        }
    }

    function modifiernewsAction(Request $request, $id)
    {

        $em=$this->getDoctrine()->getManager();
        $p= $em->getRepository(News::class)->find(37);
        //$picture = $p->getImage();
        // var_dump($picture);die();
        $picture = $p->getImage();

        $form=$this->createForm(NewsType::class,$p)->handleRequest($request); // fel star hedha tetn7a el image le9dima//chnou taw walet tekhdem? nn att





        if($form->isSubmitted() && $form->isValid()){
            //   var_dump($picture);die();



            /** @var UploadedFile $file */
            $file = $p->getImage();
            var_dump($file);
            if ($file !== null) {
                $filename = md5(uniqid(rand(), true)) . '.' . $file->guessExtension();
                /** @var TYPE_NAME $file */
                $file->move($this->getParameter('photos_directory'), $filename);
                $p->setImage($filename);
            } else
                $p->setImage($picture);

            //     $filename= md5(uniqid(rand(), true))  . '.' . $file->guessExtension();
            // $file->move($this->getParameter('photos_directory'), $filename);
            //   $p->setImage($filename);
            //    var_dump($p);die();
            $em->persist($p);
            $em->flush();
            return $this->redirectToRoute('afficherNews');


        }
        return $this->render('@News/news/modifier.html.twig', array(
            "f"=> $form->createView()
        ));

    }



    function supprimernewsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $News = $this->getDoctrine()->getRepository(News::class)
            ->find($id);
        $em->remove($News);
        $em->flush();
        return $this->redirectToRoute('afficherNews');
    }
    public function afficherAction(request $request)
    {
        $em =$this->getDoctrine()->getManager();
        $News = $em->getRepository("NewsBundle:News")->findAll();
        $new = $em->getRepository("NewsBundle:News")->find(38);
        $neww = $em->getRepository("NewsBundle:News")->find(39);
        $newww = $em->getRepository("NewsBundle:News")->find(40);

        $paginator  = $this->get('knp_paginator');

        $result = $paginator->paginate(
            $News,
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 3)/*limit per page*/
        );


        return $this->render('NewsBundle:news:afficher.html.twig', [
            'News' => $result, 'new'=>$new, 'neww'=>$neww, 'newww'=>$newww
        ]);


    }
    public function listAction(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request)
    {
        $dql   = "SELECT a FROM NewsBundle:News a";
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        // parameters to template
        return $this->render('@News/news/afficher.html.twig', ['pagination' => $pagination]);
    }
    public function listnewsAction(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request)
    {
        $dql   = "SELECT a FROM NewsBundle:News a";
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        // parameters to template
        return $this->render('@News/news/afficherNews.html.twig', ['pagination' => $pagination]);
    }


    public function consulterfrontAction(Request $request, $id)
    {
        $comment = new Comment();
        $em =$this->getDoctrine()->getManager();
        $News = $em->getRepository("NewsBundle:News")->find($id);
        $previous = $id-1;
        $next = $id+1;
        $Newsp = $em->getRepository("NewsBundle:News")->find($previous);
        $Newsn = $em->getRepository("NewsBundle:News")->find($next);
        $form = $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {

            $user = $this->get('security.token_storage')->getToken()->getUser();
            $comment->setUser($user);
            $comment->setNews($News);
            $comment->setCreatedAt(new \DateTime());
            $em =$this->getDoctrine()->getManager();
            $userconnected=$this->getUser();
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute("consulterfront", array('id' => $News->getId()));

        }

        $emm =$this->getDoctrine()->getManager();
        $Comment = $emm->getRepository("NewsBundle:Comment")->ShowCmtBlog($id);


        return $this->render("@News/news/consulter.html.twig",array(
            'form'=>$form->createView(),
            'News'=>$News,
            'Newsn'=>$Newsn,
            'Newsp'=>$Newsp,
            'Comment'=>$Comment));

    }

    public function LikeAction(Request $request)
    { $session = $request->getSession();
        $em=$this->getDoctrine()->getManager();
        $idc = $request->get('idc');
        $p=$this->getDoctrine()->getRepository(News::class)->find($idc);
        $p->setPoint($p->getPoint()+1);
        $em->persist($p);
        $em->flush();

        return $this->redirectToRoute('consulterfront',array('id' => $p->getId()
        ));
    }


    //Form Mobile

    public function ListNewsMobileAction(){
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT n
        FROM NewsBundle:News n '
        );
        $News = $query->getArrayResult();
        $response = new Response(json_encode($News));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function  AddNewsMobileAction(Request $request){

        $titre = $request->query->get('titre');

        $image = $request->query->get('image');

        $descr = $request->query->get('descr');

        $News = new News();
        $News->setTitre($titre);

        $News->setImage($image);
        $News->setIntroduction("Hello");
        $News->setDescr($descr);
        $News->setViews(1);
        $News->setPoint(0);

        $em=$this->getDoctrine()->getManager();


        try {
            $em->persist($News);
            $em->flush();

        } catch(\Exception $ex)
        {
            $data = [
                'title' => 'validation error',
                'message' => 'Some thing went Wrong',
                'errors' => $ex->getMessage()
            ];
            $response = new JsonResponse($data,400);
            return $response;
        }

        return $this->json(array('title'=>'successful','message'=> "News added successfully"),200);


    }

    public function deleteNewsMobileAction(Request $request){
        $id = $request->query->get('id');
        $News= $this->getDoctrine()->getRepository('NewsBundle:News')->findOneById($id);
        if($News){
            $em = $this->getDoctrine()->getManager();
            $em->remove($News);
            $em->flush();
            $response = array("body"=> "News delete");
        }else{
            $response = array("body"=>"Error");
        }
        return new JsonResponse($response);
    }
    public function  EditNewsMobileAction(Request $request){

        $id = $request->query->get('id');
        $em=$this->getDoctrine()->getManager();
        $News=$em->getRepository(News::class)->find($id);
        $titre = $request->query->get('titre');
        $descr = $request->query->get('descr');


        $News->setTitre($titre);
        $News->setDescr($descr);
        try {
            $em->persist($News);
            $em->flush();
        }
        catch(\Exception $ex)
        {
            $data = [
                'title' => 'validation error',
                'message' => 'Some thing went Wrong',
                'errors' => $ex->getMessage()
            ];
            $response = new JsonResponse($data,400);
            return $response;
        }
        return $this->json(array('title'=>'successful','message'=> "News Edited successfully"),200);
    }

    ///SELECT  n.titre ,count(c.news_id) as nombre FROM comment c, news n WHERE c.news_id=n.id  GROUP by n.id
    ///
    public function StatNewsMobileAction(){
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT n.titre ,count(c.news) as nombre
        FROM NewsBundle:News n ,NewsBundle:Comment c WHERE c.news=n.id  GROUP by n.id'
        );
        $NEWS = $query->getArrayResult();
        $response = new Response(json_encode($NEWS));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    //COMMENT MOBILE
    public function ListCommentMobileAction(){
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT c
        FROM NewsBundle:Comment c '
        );
        $Comment = $query->getArrayResult();
        $response = new Response(json_encode($Comment));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function deleteCommentMobileAction(Request $request){
        $id = $request->query->get('id');
        $Comment= $this->getDoctrine()->getRepository('NewsBundle:Comment')->findOneById($id);
        if($Comment){
            $em = $this->getDoctrine()->getManager();
            $em->remove($Comment);
            $em->flush();
            $response = array("body"=> "Comment delete");
        }else{
            $response = array("body"=>"Error");
        }
        return new JsonResponse($response);
    }
    public function  EditCommentMobileAction(Request $request){

        $id = $request->query->get('id');
        $em=$this->getDoctrine()->getManager();
        $Comment=$em->getRepository(News::class)->find($id);
        $text = $request->query->get('text');



        $Comment->setText($text);

        try {
            $em->persist($Comment);
            $em->flush();
        }
        catch(\Exception $ex)
        {
            $data = [
                'title' => 'validation error',
                'message' => 'Some thing went Wrong',
                'errors' => $ex->getMessage()
            ];
            $response = new JsonResponse($data,400);
            return $response;
        }
        return $this->json(array('text'=>'successful','message'=> "Comment Edited successfully"),200);
    }
    public function  AddCommentMobileAction(Request $request){

        $Comment = new Comment();
        $em=$this->getDoctrine()->getManager();
        $text=  $request->query->get('text');
        $news_id = $request->query->get('news_id');
        $user_id = $request->query->get('user_id');
        $user =$em->getRepository(User::class)->find($user_id);
        $news =$em->getRepository(News::class)->find($news_id);

        $Comment->setCreatedAt(new \DateTime());
        $Comment->setText($text);
        $Comment->setUser($user);
        $Comment->setNews($news);



        try {
            $em->persist($Comment);
            $em->flush();

        } catch(\Exception $ex)
        {
            $data = [
                'text' => 'validation error',
                'message' => 'Some thing went Wrong',
                'errors' => $ex->getMessage()
            ];
            $response = new JsonResponse($data,400);
            return $response;
        }

        return $this->json(array('text'=>'successful','message'=> "Comment added successfully"),200);


    }

    /**
     * Creates a new ActionItem news.
     *
     * @Route("/search", name="ajax_search")
     * @Method("GET")
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $news = $em->getRepository('NewsBundle:News')->findEntitiesByString($requestString);
        if (!$news) {
            $result['news']['error'] = "NEWS Not found :( ";

        } else {
            $result['news'] = $this->getRealEntities($news);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($news)
    {
        foreach ($news as $new) {
            $realEntities[$new->getId()] = [$new->getImage(),$new->getTitre()];

        }
        return $realEntities;
    }

}

