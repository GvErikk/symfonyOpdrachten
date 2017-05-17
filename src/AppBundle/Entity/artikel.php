<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * artikel
 *
 * @ORM\Table(name="artikel")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\artikelRepository")
 * @UniqueEntity("artikelnummer",  message = "Dit artikelnummer bestaat al.")
 */
class artikel
{

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="artikelnummer", type="string", length=255, unique=true)
     */
    private $artikelnummer;

    /**
     * @var string
     *
     * @ORM\Column(name="omschrijving", type="string", length=255)
     */
    private $omschrijving;

    /**
     * @var string
     *
     * @ORM\Column(name="technishe_specificaties", type="string", length=255, nullable=true)
     */
    private $technisheSpecificaties;

    /**
     * @var string
     *
     * @ORM\Column(name="magazijnlocatie", type="string", length=30)
     * @Assert\Regex("/^[0-26]\W{1}[a-zA-Z]{1}0[1-9]|1[0-0]$/")
     * @Assert\Length(max="6")
     */
    private $magazijnlocatie;

    /**
     * @var int
     *
     * @ORM\Column(name="inkoopprijs", type="integer")
     * @Assert\GreaterThan(
     *     value = 0,
     *     message = "De inkoopprijs moet boven {{ compared_value }} zijn."
     * )
     */
    private $inkoopprijs;

    /**
     * @var int
     *
     * @ORM\Column(name="vervangend_artikel", type="integer", nullable=true)
     */
    private $vervangendArtikel;

    /**
     * @var int
     *
     * @ORM\Column(name="minimum_voorraad", type="integer")
     * @Assert\GreaterThan(
     *     value = 0,
     *     message = "De voorraad moet boven {{ compared_value }} zijn."
     * )
     *
     */
    private $minimumVoorraad;

    /**
     * @var int
     *
     * @ORM\Column(name="vooraad", type="integer")
     * @Assert\GreaterThan(
     *     value = 0,
     *     message = "De voorraad moet boven {{ compared_value }} zijn."
     * )
     *
     */
    private $vooraad;

    /**
     * @var int
     *
     * @ORM\Column(name="bestelserie", type="integer")
     */
    private $bestelserie;


    /**
     * Set artikelnummer
     *
     * @param string $artikelnummer
     *
     * @return artikel
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
     * Set omschrijving
     *
     * @param string $omschrijving
     *
     * @return artikel
     */
    public function setOmschrijving($omschrijving)
    {
        $this->omschrijving = $omschrijving;

        return $this;
    }

    /**
     * Get omschrijving
     *
     * @return string
     */
    public function getOmschrijving()
    {
        return $this->omschrijving;
    }

    /**
     * Set technisheSpecificaties
     *
     * @param string $technisheSpecificaties
     *
     * @return artikel
     */
    public function setTechnisheSpecificaties($technisheSpecificaties)
    {
        $this->technisheSpecificaties = $technisheSpecificaties;

        return $this;
    }

    /**
     * Get technisheSpecificaties
     *
     * @return string
     */
    public function getTechnisheSpecificaties()
    {
        return $this->technisheSpecificaties;
    }

    /**
     * Set magazijnlocatie
     *
     * @param string $magazijnlocatie
     *
     * @return artikel
     */
    public function setMagazijnlocatie($magazijnlocatie)
    {
        $this->magazijnlocatie = $magazijnlocatie;

        return $this;
    }

    /**
     * Get magazijnlocatie
     *
     * @return string
     */
    public function getMagazijnlocatie()
    {
        return $this->magazijnlocatie;
    }

    /**
     * Set inkoopprijs
     *
     * @param integer $inkoopprijs
     *
     * @return artikel
     */
    public function setInkoopprijs($inkoopprijs)
    {
        $this->inkoopprijs = $inkoopprijs;

        return $this;
    }

    /**
     * Get inkoopprijs
     *
     * @return int
     */
    public function getInkoopprijs()
    {
        return $this->inkoopprijs;
    }

    /**
     * Set vervangendArtikel
     *
     * @param integer $vervangendArtikel
     *
     * @return artikel
     */
    public function setVervangendArtikel($vervangendArtikel)
    {
        $this->vervangendArtikel = $vervangendArtikel;

        return $this;
    }

    /**
     * Get vervangendArtikel
     *
     * @return int
     */
    public function getVervangendArtikel()
    {
        return $this->vervangendArtikel;
    }

    /**
     * Set minimumVoorraad
     *
     * @param integer $minimumVoorraad
     *
     * @return artikel
     */
    public function setMinimumVoorraad($minimumVoorraad)
    {
        $this->minimumVoorraad = $minimumVoorraad;

        return $this;
    }

    /**
     * Get minimumVoorraad
     *
     * @return int
     */
    public function getMinimumVoorraad()
    {
        return $this->minimumVoorraad;
    }

    /**
     * Set vooraad
     *
     * @param integer $vooraad
     *
     * @return artikel
     */
    public function setVooraad($vooraad)
    {
        $this->vooraad = $vooraad;

        return $this;
    }

    /**
     * Get vooraad
     *
     * @return int
     */
    public function getVooraad()
    {
        return $this->vooraad;
    }

    /**
     * Set bestelserie
     *
     * @param integer $bestelserie
     *
     * @return artikel
     */
    public function setBestelserie($bestelserie)
    {
        $this->bestelserie = $bestelserie;

        return $this;
    }

    /**
     * Get bestelserie
     *
     * @return int
     */
    public function getBestelserie()
    {
        return $this->bestelserie;
    }
}

