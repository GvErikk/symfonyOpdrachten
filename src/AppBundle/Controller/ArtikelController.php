<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;;

use AppBundle\Entity\artikel;
use AppBundle\Form\Type\ArtikelType;
use AppBundle\Form\Type\ArtikelTypeMagazijn;




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
        return new Response($this->render('pages/form_nieuw_artikel_inkoper.html.twig', array('form' => $form->createView())));
    }

    /**
     * @Route("/inkoper/artikelen", name="alleartikelen")
     */
    public function alleArtikelen(Request $request){
        //alle artikelen omhalen
        $artikelen = $this->getDoctrine()->getRepository("AppBundle:artikel")->findAll();
        //wegschrijven naar html bestand met de artikelen variable
        return new Response($this->render('pages/alle_artikellen_inkoper.html.twig', array('artikelen' => $artikelen)));
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

        return new Response($this->render('pages/form_wijzigen_artikel_inkoper.html.twig', array('form' => $form->createView())));
    }

    /**
     * @Route("/inkoper/artikel/verwijder/{artikelnummer}", name="artikelverwijderen")
     */
    public function verwijderArtikel($artikelnummer)
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
        return new Response($this->render('pages/artikel_overzicht_inkoper.html.twig', array('artikel' => $artikel)));
    }

    /**
     * @Route("/inkoper/zoek", name="zoekArtikel")
     */
    public function liveSearchAction(Request $request)
    {
        //ophalen post data van ajax call
        $string = $_POST['searchText'];
        //maken select statement om alle artikelnummers op te halen
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = "SELECT * FROM artikel WHERE artikelnummer LIKE '%".$string."%'";
        //query uitvoeren
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();

        //omzeten van data naar jason formaat
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($result, 'json');
        $response = new Response($jsonContent);

        //terug sturen van data
        return $response;
    }




    /**
     * @Route("/magazijn/artikelen", name="alleartikelenmazijn")
     */
    public function alleArtikelenMagazijn(Request $request){
        //alle artikelen omhalen
        $artikelen = $this->getDoctrine()->getRepository("AppBundle:artikel")->findAll();
        //wegschrijven naar html bestand met de artikelen variable
        return new Response($this->render('pages/alle_artikellen_magazijn.html.twig', array('artikelen' => $artikelen)));
    }

    /**
     * @Route("/magazijn/artikel/{artikelnummer}", name="artikelMagazijn")
     */
    public function getArtikelMagazijn($artikelnummer){
        $artikel = $this->getDoctrine()->getRepository("AppBundle:artikel")->find($artikelnummer);
        return new Response($this->render('pages/artikel_overzicht_magazijn.html.twig', array('artikel' => $artikel)));
    }

    /**
     * @Route("/magazijn/artikel/wijzig/{artikelnummer}", name="artikelwijzigenmagazijn")
     */
    public function wijzigArtikelMagazijn(Request $request, $artikelnummer) {
        $bestaandartikel = $this->getDoctrine()->getRepository("AppBundle:artikel")->find($artikelnummer);
        $form = $this->createForm(ArtikelTypeMagazijn::class, $bestaandartikel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bestaandartikel);
            $em->flush();
            return $this->redirect($this->generateurl("alleartikelenmazijn"));
        }

        return new Response($this->render('pages/form_wijzigen_artikel_inkoper.html.twig', array('form' => $form->createView())));
    }



}
