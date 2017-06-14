<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ordercontrole
 *
 * @ORM\Table(name="ordercontrole")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ordercontroleRepository")
 */
class ordercontrole
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
     * @ORM\Column(name="orderdetailid", type="string")
     */
    private $orderdetailid;

    /**
     * @var int
     *
     * @ORM\Column(name="kwaliteit", type="integer")
     */
    private $kwaliteit;


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
     * Set orderdetailid
     *
     * @param integer $orderdetailid
     *
     * @return ordercontrole
     */
    public function setOrderdetailid($orderdetailid)
    {
        $this->orderdetailid = $orderdetailid;

        return $this;
    }

    /**
     * Get orderdetailid
     *
     * @return int
     */
    public function getOrderdetailid()
    {
        return $this->orderdetailid;
    }

    /**
     * Set kwaliteit
     *
     * @param integer $kwaliteit
     *
     * @return ordercontrole
     */
    public function setKwaliteit($kwaliteit)
    {
        $this->kwaliteit = $kwaliteit;

        return $this;
    }

    /**
     * Get kwaliteit
     *
     * @return int
     */
    public function getKwaliteit()
    {
        return $this->kwaliteit;
    }
}

