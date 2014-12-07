<?php

namespace Yu\BobToWebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Player
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Yu\BobToWebBundle\Entity\PlayerRepository")
 */
class Player
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     *
     * @ORM\ManyToOne(targetEntity="Yu\BobToWebBundle\Entity\Server")
     */
    private $server;


    public function getServer(){
        return $this->server;
    }

    public function setServer($server){
        $this->server = $server;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Player
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
