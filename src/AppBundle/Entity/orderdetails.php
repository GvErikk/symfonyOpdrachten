<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * orderdetails
 *
 * @ORM\Table(name="orderdetails")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\orderdetailsRepository")
 */
class orderdetails
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
     * @ORM\Column(name="orderId", type="integer")
     * @ORM\ManyToOne(targetEntity="orders", inversedBy="orderdetails")
     * @ORM\JoinColumn(name="orders", referencedColumnName="id")
     */
    private $orderId;

    /**
     * @var string
     *
     * @ORM\OneToOne(targetEntity="artikel")
     * @ORM\JoinColumn(name="artikelnummer", referencedColumnName="artikelnummer")
     */
    private $artikelnummer;

    /**
     * @var int
     *
     * @ORM\Column(name="Aantal", type="integer")
     * @Assert\GreaterThan(
     *     value = 0,
     *     message = "Het aantal moet boven {{ compared_value }} zijn."
     * )
     */
    private $aantal;


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
     * Set orderId
     *
     * @param integer $orderId
     *
     * @return orderdetails
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId
     *
     * @return int
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set artikelnummer
     *
     * @param string $artikelnummer
     *
     * @return orderdetails
     */
    public function setArtikelnummer($artikelnummer)
    {
        $this->artikelnummer = $artikelnummer;

        return $this;
    }

    /**
     * Get artikelnummer
     *
     * @return string
     */
    public function getArtikelnummer()
    {
        return $this->artikelnummer;
    }

    /**
     * Set aantal
     *
     * @param integer $aantal
     *
     * @return orderdetails
     */
    public function setAantal($aantal)
    {
        $this->aantal = $aantal;

        return $this;
    }

    /**
     * Get aantal
     *
     * @return int
     */
    public function getAantal()
    {
        return $this->aantal;
    }
}

