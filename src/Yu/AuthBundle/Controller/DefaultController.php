<?php

namespace Yu\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        var_dump("WESH");
        return $this->render('YuAuthBundle:Default:index.html.twig', array('name' => $name));
    }
}
