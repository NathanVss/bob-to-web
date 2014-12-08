<?php

namespace Yu\BobToWebBundle\Controller;

use Yu\BobToWebBundle\Form\LogsFileType;
use Yu\BobToWebBundle\Form\ServerType;

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

    public function managePlayersAction(Request $request) {

        
        return $this->render('YuBobToWebBundle:Players:managePlayers.html.twig');
    }

    public function manageServersAction(Request $request) {
        $serverRepository = $this->getDoctrine()->getManager()->getRepository('YuBobToWebBundle:Server');
        $ServersManager = $this->container->get('yu_bob_to_web.serversManager');
        $formBuilder = $this->get('form.factory')->createBuilder('form');

        $Servers = $serverRepository->findAll();
        $formBuilder->add('checkFiles', 'submit');
        $checkFilesForm = $formBuilder->getForm();

        $serverForms = array();
        foreach($Servers as $key => $Server) {
            $serverForms[] = $this->get('form.factory')->createNamed($key, new ServerType, $Server);
        }
        // var_dump($request);
        if($checkFilesForm->handleRequest($request)->isValid()) {
            $ServersManager->checkFiles();
            return $this->redirect($request->getUri());
        }

        foreach($serverForms as $serverForm) {

            if($serverForm->handleRequest($request)->isValid()) {
                $Server = $serverForm->getData();
                if($serverForm->get('Save')->isClicked()) {
                    
                    $this->getDoctrine()->getManager()->persist($Server);
                                      
                } else {

                    $this->getDoctrine()->getManager()->remove($Server);
                }
                $this->getDoctrine()->getManager()->flush(); 
                return $this->redirect($request->getUri());
            }
        }

        foreach($serverForms as $key => $serverForm) {
            $serverForms[$key] = $serverForm->createView();
        }

        return $this->render('YuBobToWebBundle:Servers:manageServers.html.twig', array(
            'checkFilesForm' => $checkFilesForm->createView(),
            'serverForms' => $serverForms));
    }



    public function manageLogsFilesAction(Request $request) {
        $Logger = $this->container->get('yu_bob_to_web.logger');
        $repository = $this->getDoctrine()->getManager()->getRepository('YuBobToWebBundle:LogsFile');
        $LogsFiles = $repository->findAll();

        $forms = array();
        foreach($LogsFiles as $key => $LogsFile) {

            $forms[] = $this->get('form.factory')->createNamed($key, new LogsFileType, $LogsFile);

        }
        foreach($forms as $key => $form) {

            if($form->handleRequest($request)->isValid()) {

                $CurLogsFile = $form->getData();
                if($form->get('Save')->isClicked()) {
                    
                    $this->getDoctrine()->getManager()->persist($CurLogsFile);
                                    
                } elseif($form->get('Update')->isClicked()) {
                    $Logger->updateLogsFile($CurLogsFile);
                }
                $this->getDoctrine()->getManager()->flush();   

            }
        }
        foreach($forms as $key => $form) {

            $forms[$key] = $form->createView();
        }
        
        return $this->render('YuBobToWebBundle:Logger:manageLogsFiles.html.twig', array('forms' => $forms));
    }
}
