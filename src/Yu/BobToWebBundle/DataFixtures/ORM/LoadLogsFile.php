<?php
// src/OC/PlatformBundle/DataFixtures/ORM/LoadCategory.php

namespace Yu\BobToWeb\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Yu\BobToWebBundle\Entity\LogsFile;

class LoadLogsFile implements FixtureInterface
{
  // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
  public function load(ObjectManager $manager)
  {
    // Liste des noms de catégorie à ajouter
    $paths = array(
      'C:/Users/Nathan/Documents/M2Bob/M2Bob - Version/M2Bob - Version 3.9.8/Resources/Userdata/Logs/Jeltao.log',
      'C:/Users/Nathan/Documents/M2Bob/M2Bob - Version/M2Bob - Version 3.9.8/Resources/Userdata/Logs/Moelate.log',
      'C:/Users/Nathan/Documents/M2Bob/M2Bob - Version/M2Bob - Version 3.9.8/Resources/Userdata/Logs/makisa.log'
    );

    foreach ($paths as $path) {
      $LogsFile = new LogsFile();
      $LogsFile->setPath($path);
      $LogsFile->setLastSize(time());
      

      $manager->persist($LogsFile);
    }

    // On déclenche l'enregistrement de toutes les catégories
    $manager->flush();
  }
}