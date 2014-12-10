<?php

namespace Yu\BobToWebBundle\Logger;

use Yu\BobToWebBundle\Entity\Log;
use Yu\BobToWebBundle\Entity\LogsFile;

class YuLogger {

	private $em;
	private $LogsFiles;
	private $Container;

	public function __construct($Container) {

		$this->Container = $Container;
		$this->em = $this->Container->get('doctrine')->getManager();
		$this->LogsFiles = $this->em->getRepository('YuBobToWebBundle:LogsFile')->findAll();

	}

	public function checkStatusFiles() {
		$oldContents = $this->getStatusFiles();
		// var_dump($oldContents);
		sleep(2);
		$curContents = $this->getStatusFiles();
		$updatedContents = array();
		foreach($oldContents as $key => $oldContent) {
			if($oldContents[$key]['timestamp'] != $curContents[$key]['timestamp']) {

				$updatedContents[$key] = $curContents[$key];

				// if(isset($oldContents[$key]['name'])) {
				// 	var_dump("### ".$oldContents[$key]['name']." ###");
				// }
				// var_dump("before :");
				// var_dump($oldContents[$key]['date']);
				// var_dump("now : ");
				// var_dump($curContents[$key]['date']);
				// var_dump("");
				// var_dump("");
				// var_dump("");
			}
		}
		return $updatedContents;
		// var_dump($updatedContents);

	}

	public function getStatusFiles() {
		$path = "C:/Users/Nathan/Documents/M2Bob/M2Bob - Version/M2Bob - Version 3.9.8/Resources/Userdata/CharSettings";
		$handle = opendir($path);
		$files = array();

		while($entry = readdir($handle)) {
			if($entry != '.' && $entry != '..' && strstr($entry, '_status.bob')) {
				$files[] = $entry;
			}
		}


		$contents = array();
		foreach($files as $file) {
			$content = file_get_contents($path.'/'.$file);
			$content = str_replace("\r\n", "_|_", $content);
			
			if(preg_match("#Logging=(?<timestamp>[0-9]+)#", $content, $matches)) {

				$contents[$file] = array('content' => $content);
				$contents[$file]['timestamp'] = $matches['timestamp'];
				$contents[$file]['date'] = date("H:i:s d-m-Y", $matches['timestamp']);

				if(preg_match("#Nick=(?<name>[a-zA-Z0-9]+)#", $contents[$file]['content'], $matches)) {
					$contents[$file]['name'] = $matches['name'];
				}

				if(preg_match("#IsIngame=(?<isInGame>[a-zA-Z]+)#", $contents[$file]['content'], $matches)) {
					$contents[$file]['isInGame'] = $matches['isInGame'];
				}
				
				if(preg_match("#Status=(?<status>[a-zA-Z\s]+)#", $contents[$file]['content'], $matches)) {
					$contents[$file]['status'] = $matches['status'];
				}				
				
				if(preg_match("#HP=(?<hp>[0-9]+)#", $contents[$file]['content'], $matches)) {
					$contents[$file]['hp'] = $matches['hp'];
				}				
				if(preg_match("#EXP=(?exp>[0-9]+)#", $contents[$file]['content'], $matches)) {
					$contents[$file]['exp'] = $matches['exp'];
				}

			}
		}
		return $contents;
	}

	public function updateLogsFile($LogsFile) {

		if($LogsFile->getPlayer() == null) {
			$content = file_get_contents($LogsFile->getPath());
			$content = explode("\r\n", $content);

			$PlayersManager = $this->Container->get('yu_bob_to_web.playersManager');
			if(preg_match("#\[([a-zA-Z0-9]+):\s+([0-9\.]+),\s([0-9:]+)\]\s-->\s([a-zA-Z0-9:,\s]+)#", $content[0], $matches)) {

				$Player = $PlayersManager->createIfNotExists($matches[1]);
				if($Player) {
					$LogsFile->setPlayer($Player);
					$this->em->persist($LogsFile);
					$this->em->flush();
				}
			}
		}

	}

	public function mustUpdate($LogsFile) {

		$curSize = filesize($LogsFile->getPath());

		if($curSize != $LogsFile->getLastSize()) {

			$LogsFile->setLastSize($curSize);
			$LogsFile->setLastCheckTime(new \Datetime("now"));;
			// var_dump($LogsFile->getLastCheckTime());
			$this->em->persist($LogsFile);
			$this->em->flush();
			return true;
		}

		return false;
	}

	public function getLogFromScratch($datas) {

		if(preg_match("#\[([a-zA-Z0-9]+):\s+([0-9\.]+),\s([0-9:]+)\]\s-->\s([a-zA-Z0-9:,\s]+)#", $datas, $matches)) {
			$name = $matches[1];
			$date = $matches[2];
			$hour = $matches[3];
			$content = $matches[4];
			$dateFormated = $this->reverseDate($date);
			$timeFormated = $dateFormated.' '.$hour;
			$Log = new Log();
			$Log->setTime(strtotime($timeFormated));
			$Log->setContent($content);
			$Log->setCharacterName($name);
			return $Log;
		}		
		return false;

	}

	// TODO : Se baser sur la taille modifée(?) du fichier
	public function updateLogs() {
		$repository = $this->em->getRepository('YuBobToWebBundle:Log');
		foreach($this->LogsFiles as $LogsFile) {
			// var_dump($LogsFile);
			// var_dump($LogsFile);
			if($this->mustUpdate($LogsFile)) {
				// var_dump('must update this one');
				$content = file_get_contents($LogsFile->getPath());
				$logs = explode("\r\n", $content);

				foreach($logs as $log) {
					// var_dump($log);
					if($Log = $this->getLogFromScratch($log)) {
						// var_dump($Log);
					
						if(!$repository->findBy(array('time' => $Log->getTime(), 'characterName' => $Log->getCharacterName()))) {
							$this->em->persist($Log);
						}
					}
				}

				
			}

		}
		$this->em->flush();
	}

	/*
	 * Date dd/mm/yyyy -> yyyy/mm/dd
	 */
	public function reverseDate($date) {

		if(preg_match('#(\d+).(\d+).(\d+)#', $date, $matches)) {
			$day = $matches[1];
			$month = $matches[2];
			$year = $matches[3];
			return $year.'-'.$month.'-'.$day;

		}
		return false;
	}

}

?>