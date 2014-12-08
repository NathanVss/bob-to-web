<?php

namespace Yu\BobToWebBundle\Server;

use Yu\BobToWebBundle\Entity\Log;
use Yu\BobToWebBundle\Entity\Server;
use Yu\BobToWebBundle\Entity\LogsFile;

class ServersManager {

	private $serverFilePath;
	private $em;
	private $Container;

	public function __construct($Container) {
		
		$this->Container = $Container;
		$this->em = $this->Container->get('doctrine')->getManager();
		$this->serverFilePath = "C:/Users/Nathan/Documents/M2Bob/M2Bob - Version/M2Bob - Version 3.9.8/Resources/Userdata/Serverlist.ini";
		
	}

	public function checkFiles() {
		$content = file_get_contents($this->serverFilePath);
		$content = preg_replace("#([0-9]+\.+\s+[a-zA-Z]+)#", "_|_$1", $content);
		$content = explode("_|_", $content);

		$serversNb = $content[0];
		$serversParams = array();
		foreach($content as $key => $serverParams) {
			if($key != 0) {
				$serversParams[] = $serverParams;
			}
		}

		$Servers = array();
		foreach($serversParams as $serverParams) {
			$Servers[] = $this->getServerFromRawParams($serverParams);
		}

		$repository = $this->em->getRepository('YuBobToWebBundle:Server');
		foreach($Servers as $Server) {
			// var_dump($Server);
			$Exist = $repository->findBy(array('name' => $Server->getName(), 'channelsNb' => $Server->getChannelsNb()));
			// var_dump($Exist);
			if(!$Exist) {
				// var_dump("REPOSITORY");
				// var_dump($Server);
				$this->em->persist($Server);
			}			
		}

		$this->em->flush();
	}

	public function getServerFromRawParams($params) {
		

		$Server = new Server();
		$params = explode("\r\n", $params);
		$Server->setChannelsNb($params[2]);
		if(preg_match("#^[0-9]+\. ([a-zA-Z]+)#", $params[0], $matches)) {
			$Server->setName($matches[1]);
		}

		return $Server;
	}

}