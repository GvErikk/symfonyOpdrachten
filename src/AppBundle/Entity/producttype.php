<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * producttype
 *
 * @ORM\Table(name="producttype")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\producttypeRepository")
 */
class producttype
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $tid;

    /**
     * @var string
     *
     * @ORM\Column(name="beschrijving", type="string", length=100)
     */
    private $beschrijving;



    /**
     * Set tid
     *
     * @param integer $tid
     *
     * @return producttype
     */
    public function setTid($tid)
    {
        $this->tid = $tid;

        return $this;
    }

    /**
     * Get tid
     *
     * @return int
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * Set beschrijving
     *
     * @param string $beschrijving
     *
     * @return producttype
     */
    public function setBeschrijving($beschrijving)
    {
        $this->beschrijving = $beschrijving;

        return $this;
    }

    /**
     * Get beschrijving
     *
     * @return string
     */
    public function getBeschrijving()
    {
        return $this->beschrijving;
    }
}

