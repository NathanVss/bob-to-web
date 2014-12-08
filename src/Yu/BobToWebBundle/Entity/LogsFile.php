<?php

namespace Yu\BobToWebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogsFile
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Yu\BobToWebBundle\Entity\LogsFileRepository")
 */
class LogsFile
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
     * @ORM\Column(name="path", type="string", length=600)
     */
    private $path;

    /**
     * @var integer
     *
     * @ORM\Column(name="lastSize", type="integer", nullable=true)
     */
    private $lastSize;

    /**
     * @var integer
     *
     * @ORM\Column(name="lastCheckTime", type="datetime", nullable=true)
     */
    private $lastCheckTime;

    /**
     * @ORM\OneToOne(targetEntity="Yu\BobToWebBundle\Entity\Player", cascade={"persist"})
     * @ORM\joinColumn(onDelete="SET NULL")
     */
    private $player;

    public function __construct() {

        $this->lastCheckTime = new \Datetime();
    }

    public function getPlayer(){
        return $this->player;
    }

    public function setPlayer($player){
        $this->player = $player;
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
     * Set path
     *
     * @param string $path
     * @return LogsFile
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set lastSize
     *
     * @param integer $lastSize
     * @return LogsFile
     */
    public function setLastSize($lastSize)
    {
        $this->lastSize = $lastSize;

        return $this;
    }

    /**
     * Get lastSize
     *
     * @return integer 
     */
    public function getLastSize()
    {
        return $this->lastSize;
    }

    /**
     * Set lastCheckTime
     *
     * @param integer $lastCheckTime
     * @return LogsFile
     */
    public function setLastCheckTime($lastCheckTime)
    {
        $this->lastCheckTime = clone $lastCheckTime;

        return $this;
    }

    /**
     * Get lastCheckTime
     *
     * @return integer 
     */
    public function getLastCheckTime()
    {
        return clone $this->lastCheckTime;
    }
}
