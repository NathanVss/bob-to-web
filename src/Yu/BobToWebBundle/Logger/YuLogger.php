<?php

namespace Yu\BobToWebBundle\Logger;

use Yu\BobToWebBundle\Entity\Log;

class YuLogger {

	private $filesToCheck;
	private $em;
	public function __construct($doctrine) {
		$this->em = $doctrine->getManager();
		$this->filesToCheck = array('C:/Users/Nathan/Documents/M2Bob/M2Bob - Version/M2Bob - Version 3.9.8/Resources/Userdata/Logs/Jeltao.log');

	}

	// TODO : Se baser sur la taille modifée(?) du fichier
	public function updateLogs() {

		foreach($this->filesToCheck as $fileToCheck) {
			$repository = $this->em->getRepository('YuBobToWebBundle:Log');
			$content = file_get_contents($fileToCheck);
			$logs = explode("\r\n", $content);
			// var_dump($logs);
			foreach($logs as $log) {
				if(preg_match("#\[([a-zA-Z0-9]+):\s([0-9\.]+),\s([0-9:]+)\]\s-->\s([a-zA-Z0-9\s]+)#", $log, $matches)) {
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
					if(!$repository->findBy(array('time' => $Log->getTime(), 'characterName' => $Log->getCharacterName()))) {
						var_dump('persisting this one.');
						$this->em->persist($Log);
					}
					//
					var_dump($Log);
				}
			}

			$this->em->flush();
			//echo "<pre>".str_replace('\r\n', "\r\n", json_encode($content, JSON_PRETTY_PRINT))."</pre>";
		}
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