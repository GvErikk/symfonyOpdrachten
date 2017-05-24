<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ordersRepository")
 */
class orders
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="Bestelordernummer", type="integer", unique=true)
     */
    private $bestelordernummer;

    /**
     * @var string
     *
     * @ORM\Column(name="Leverancier", type="string", length=100)
     */
    private $leverancier;

    /**
     * @var int
     *
     * @ORM\Column(name="Ontvangen", type="integer")
     */
    private $ontvangen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Datumontvangst", type="datetime", nullable=true)
     */
    private $datumontvangst;


    /**
     * @ORM\OneToMany(targetEntity="orderdetails", mappedBy="orders")
     *
     */
    private $orderdetails;

    /**
     * @var \Status
     *
     * @ORM\Column(name="Status", type="string")
     */
    private $status;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set bestelordernummer
     *
     * @param integer $bestelordernummer
     *
     * @return orders
     */
    public function setBestelordernummer($bestelordernummer)
    {
        $this->bestelordernummer = $bestelordernummer;

        return $this;
    }

    /**
     * Get bestelordernummer
     *
     * @return int
     */
    public function getBestelordernummer()
    {
        return $this->bestelordernummer;
    }

    /**
     * Set leverancier
     *
     * @param string $leverancier
     *
     * @return orders
     */
    public function setLeverancier($leverancier)
    {
        $this->leverancier = $leverancier;

        return $this;
    }

    /**
     * Get leverancier
     *
     * @return string
     */
    public function getLeverancier()
    {
        return $this->leverancier;
    }

    /**
     * Set ontvangen
     *
     * @param integer $ontvangen
     *
     * @return orders
     */
    public function setOntvangen($ontvangen)
    {
        $this->ontvangen = $ontvangen;

        return $this;
    }

    /**
     * Get ontvangen
     *
     * @return int
     */
    public function getOntvangen()
    {
        return $this->ontvangen;
    }

    /**
     * Set datumontvangst
     *
     * @param \DateTime $datumontvangst
     *
     * @return orders
     */
    public function setDatumontvangst($datumontvangst)
    {
        $this->datumontvangst = $datumontvangst;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param \string $status
     *
     * @return orders
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get datumontvangst
     *
     * @return \DateTime
     */
    public function getDatumontvangst()
    {
        return $this->datumontvangst;
    }

    public function __construct()
    {
        $this->orderdetails = new ArrayCollection();
    }
}

