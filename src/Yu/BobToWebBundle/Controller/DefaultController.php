<?php

namespace Yu\BobToWebBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class DefaultController extends Controller
{
    public function indexAction() {

        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();

    	$Logger = $this->container->get('yu_bob_to_web.logger');
    	$url = $this->get('router')->generate(
    			'yu_bob_to_web_ajax_getlasts',
    			array('amount' => 5)
    		);
    	$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$url;

        // OMG LOL
		return $this->render('YuBobToWebBundle:Logger:index.html.twig', array('ajaxUrlLastsLogs' => $url));  
    }
}
