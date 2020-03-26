<?php

namespace PromotionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PromotionBundle:Default:index.html.twig');
    }
}
