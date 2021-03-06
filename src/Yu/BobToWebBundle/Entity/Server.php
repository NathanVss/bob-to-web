<?php

namespace Yu\BobToWebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Server
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Yu\BobToWebBundle\Entity\ServerRepository")
 */
class Server
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
     * @ORM\Column(name="channels_nb", type="integer")
     */
    private $channelsNb;


    public function getChannelsNb(){
        return $this->channelsNb;
    }

    public function setChannelsNb($channelsNb){
        $this->channelsNb = $channelsNb;
    }

    public function __toString() {
        return $this->name;
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
     * @return Server
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
