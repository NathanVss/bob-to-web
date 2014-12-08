<?php

namespace Yu\BobToWebBundle\Player;

use Yu\BobToWebBundle\Entity\Player;

class PlayersManager {

	private $em;

	public function __construct($doctrine) {

		$this->em = $doctrine->getManager();
		
	}

	public function createIfNotExists($playerName) {
		$repository = $this->em->getRepository('YuBobToWebBundle:Player');

		$TestPlayer = $repository->findOneBy(array('name' => $playerName));
		if(!$TestPlayer) {
			$Player = new Player();
			$Player->setName($playerName);
			$this->em->persist($Player);
			$this->em->flush();
			return $Player;
		} 

		return $TestPlayer;
	}


}