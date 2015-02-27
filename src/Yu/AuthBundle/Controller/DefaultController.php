<?php

namespace Yu\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();

        if($user == null) {

            var_dump("NOT LOGGED");
        } else {

            var_dump("WESh");
        }

        return $this->render('YuAuthBundle:Default:index.html.twig');
    }
}
