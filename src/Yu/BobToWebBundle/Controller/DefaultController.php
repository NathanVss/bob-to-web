<?php

namespace Yu\BobToWebBundle\Controller;

use Yu\BobToWebBundle\Form\LogsFileType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction() {

    	$Logger = $this->container->get('yu_bob_to_web.logger');

    	$url = $this->get('router')->generate(
    			'yu_bob_to_web_ajax_getlasts',
    			array('amount' => 20)
    		);
    	$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$url;
		return $this->render('YuBobToWebBundle:Logger:liveLogs.html.twig', array('ajaxUrlLastsLogs' => $url));  
    }

    public function manageServersAction(Request $request) {
        $formBuilder = $this->get('form.factory')->createBuilder('form');
        $formBuilder
                ->add('checkFiles', 'submit');

        $checkFilesForm = $formBuilder->getForm();

        if($checkFilesForm->handleRequest($request)->isValid()) {

            

        }

        return $this->render('YuBobToWebBundle:Servers:manageServers.html.twig', array(
            'checkFilesForm' => $checkFilesForm->createView()));
    }

    public function manageLogsFilesAction(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository('YuBobToWebBundle:LogsFile');
        $LogsFiles = $repository->findAll();

        $forms = array();
        foreach($LogsFiles as $key => $LogsFile) {

            $forms[] = $this->get('form.factory')->createNamed($key, new LogsFileType, $LogsFile);

        }
        foreach($forms as $key => $form) {

            if($form->handleRequest($request)->isValid()) {

                if($form->get('Save')->isClicked()) {
                    $CurLogsFile = $form->getData();
                    $this->getDoctrine()->getManager()->persist($CurLogsFile);
                    $this->getDoctrine()->getManager()->flush();                   
                } elseif($form->get('Update')->isClicked()) {

                }

            }
        }
        foreach($forms as $key => $form) {

            $forms[$key] = $form->createView();
        }
        
        return $this->render('YuBobToWebBundle:Logger:manageLogsFiles.html.twig', array('forms' => $forms));
    }
}
