<?php

namespace Yu\BobToWebBundle\Logger;

use Yu\BobToWebBundle\Entity\Log;
use Yu\BobToWebBundle\Entity\LogsFile;

class YuLogger {

	private $em;
	private $LogsFiles;
	public function __construct($doctrine) {
		$this->em = $doctrine->getManager();
		$this->LogsFiles = $this->em->getRepository('YuBobToWebBundle:LogsFile')->findAll();

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