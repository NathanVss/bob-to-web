<?php

namespace Yu\BobToWebBundle\Controller;

use Yu\BobToWebBundle\Form\LogsFileType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction() {

        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();

    	$Logger = $this->container->get('yu_bob_to_web.logger');
        // $Logger->updateLogs();
    	$url = $this->get('router')->generate(
    			'yu_bob_to_web_ajax_getlasts',
    			array('amount' => 20)
    		);
    	$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$url;
		return $this->render('YuBobToWebBundle:Logger:liveLogs.html.twig', array('ajaxUrlLastsLogs' => $url));  
    }

    public function manageLogsFilesAction(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository('YuBobToWebBundle:LogsFile');
        $LogsFiles = $repository->findAll();

        $forms = array();
        foreach($LogsFiles as $LogsFile) {

            $forms[] = $this->get('form.factory')->create(new LogsFileType, $LogsFile);

        }

        if ($forms[0]->handleRequest($request)->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $em->persist($advert);
          $em->flush();            
        }
        // var_dump($request);
        return $this->render('YuBobToWebBundle:Logger:manageLogsFiles.html.twig', array('forms' => $forms));
    }
}
