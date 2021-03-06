<?php

namespace Yu\BobToWebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class AjaxController extends Controller
{
    public function getAllLogsAction() {



    }

    public function acdcAction() {

        $url = "http://www.stadefrance.com/";
        $firstContent = file_get_contents($url);

        sleep(2);
        $secondContent = file_get_contents($url);


        $response = new JsonResponse();
        if($secondContent != $firstContent)  {
            $response->setContent(json_encode(array('changed' => true)));
        } else {
            $response->setContent(json_encode(array('changed' => false)));
        }


        
        return $response;
    }

    public function getStatusAction() {


        $Logger = $this->container->get('yu_bob_to_web.logger');
        $status = $Logger->checkStatusFiles();

        $response = new JsonResponse();

        $response->setContent(json_encode($status));
        return $response;
    }


    public function getLastLogsAction($amount) {

		$encoders = array(new JsonEncoder());
		$normalizers = array(new GetSetMethodNormalizer());
		$serializer = new Serializer($normalizers, $encoders);
        $Logger = $this->container->get('yu_bob_to_web.logger');
        $Logger->updateLogs();

    	$repository = $this->getDoctrine()->getManager()->getRepository('YuBobToWebBundle:Log');
    	$Logs = $repository->findBy(array(), array('time' => 'desc'), $amount);

    	$response = new JsonResponse();
    	$response->setContent($serializer->serialize($Logs,'json'));
    	return $response;
    }
}
