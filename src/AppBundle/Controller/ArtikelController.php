<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\artikel;
use AppBundle\Form\Type\ArtikelType;




class ArtikelController extends Controller
{
    /**
     * @Route("/inkoper/artikel/nieuw", name="nieuwartikel")
     */
    public function nieuwproduct(Request $request) {
        $nieuwproduct = new artikel();
        $form = $this->createForm(ArtikelType::class, $nieuwproduct);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nieuwproduct);
            $em->flush();
            return $this->redirect($this->generateurl("alleartikelen"));
        }

        return new Response($this->render('form_nieuw_artikel_inkoper.html.twig', array('form' => $form->createView())));
    }

    /**
     * @Route("/inkoper/artikelen", name="alleartikelen")
     */
    public function alleArtikelen(Request $request){
        $artikelen = $this->getDoctrine()->getRepository("AppBundle:artikel")->findAll();
        $tekst = '';
//        foreach ($artikelen as $artikel){
//            $tekst .= '<a href="artikel/wijzig/'.$artikel->getArtikelnummer(). '"> '. $artikel->getArtikelnummer(). '</a> '.$artikel->getOmschrijving() . '<a href="artikel/verwijder/'.$artikel->getArtikelnummer() .'"> verwijder</a><br />';
//
//        }
        //alle_artikellen_inkoper.html
        return new Response($this->render('alle_artikellen_inkoper.html.twig', array('artikelen' => $artikelen)));
    }
    /**
     * @Route("/magazijnmeester/artikelen", name="alleartikelen")
     */
    public function magazijnArtikelen(Request $request){
        $artikelen = $this->getDoctrine()->getRepository("AppBundle:artikel")->findAll();
        $tekst = '';
//        foreach ($artikelen as $artikel){
//            $tekst .= '<a href="artikel/wijzig/'.$artikel->getArtikelnummer(). '"> '. $artikel->getArtikelnummer(). '</a> '.$artikel->getOmschrijving() . '<a href="artikel/verwijder/'.$artikel->getArtikelnummer() .'"> verwijder</a><br />';
//
//        }
        //alle_artikellen_inkoper.html
        return new Response($this->render('alle_artikellen_magazijnmeester.html.twig', array('artikelen' => $artikelen)));
    }

    public function zoekArtikelen($artikelnummer){
        $artikel = $this->getDoctrine()->getRepository("AppBundle:artikel")->find($artikelnummer);

        return new Response($this->render('alle_artikellen_inkoper.html.twig', array('artikelen' => $artikel)));
    }

    /**
     * @Route("/inkoper/artikel/wijzig/{artikelnummer}", name="artikelwijzigen")
     */
    public function wijzigArtikel(Request $request, $artikelnummer) {
        $bestaandartikel = $this->getDoctrine()->getRepository("AppBundle:artikel")->find($artikelnummer);
        $form = $this->createForm(ArtikelType::class, $bestaandartikel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bestaandartikel);
            $em->flush();
            return $this->redirect($this->generateurl("alleartikelen"));
        }

        return new Response($this->render('form_nieuw_artikel_inkoper.html.twig', array('form' => $form->createView())));
    }

    /**
     * @Route("/inkoper/artikel/verwijder/{artikelnummer}", name="artikelverwijderen")
     */
    public function verijderArtikel($artikelnummer)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $adminentities = $em->getRepository('AppBundle:artikel')->find($artikelnummer);

        $em->remove($adminentities);
        $em->flush();

        return $this->redirect($this->generateurl("alleartikelen"));
    }

    /**
     * @Route("/inkoper/artikel/{artikelnummer}", name="artikel")
     */
    public function getArtikel($artikelnummer){
        $artikel = $this->getDoctrine()->getRepository("AppBundle:artikel")->find($artikelnummer);
        return new Response($this->render('artikel_overzicht_inkoper.html.twig', array('artikel' => $artikel)));
    }

}
