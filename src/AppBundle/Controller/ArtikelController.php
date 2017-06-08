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
use AppBundle\Form\Type\ArtikelTypeWijzigen;
use AppBundle\Form\Type\ArtikelTypeMagazijn;




class ArtikelController extends Controller
{
    /**
     * @Route("/inkoper/artikel/nieuw", name="nieuwartikel")
     */
    public function nieuwproduct(Request $request) {
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
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
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/inkoper/artikelen", name="alleartikelen")
     */
    public function alleArtikelen(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            //alle artikelen omhalen
            $artikelen = $this->getDoctrine()->getRepository("AppBundle:artikel")->findBy(array('actief' => 1 ));
            //wegschrijven naar html bestand met de artikelen variable
            return new Response($this->render('pages/alle_artikellen_inkoper.html.twig', array('artikelen' => $artikelen)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/verkoper/artikelen", name="alleartikelenverkoper")
     */
    public function alleArtikelenVerkoper(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 3) {
            //alle artikelen omhalen
            $artikelen = $this->getDoctrine()->getRepository("AppBundle:artikel")->findBy(array('actief' => 1 ));
            //wegschrijven naar html bestand met de artikelen variable
            return new Response($this->render('verkoper/alle_artikellen_verkoper.html.twig', array('artikelen' => $artikelen)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/inkoper/artikel/wijzig/{artikelnummer}", name="artikelwijzigen")
     */
    public function wijzigArtikel(Request $request, $artikelnummer) {
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $bestaandartikel = $this->getDoctrine()->getRepository("AppBundle:artikel")->find($artikelnummer);
            $form = $this->createForm(ArtikelTypeWijzigen::class, $bestaandartikel);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($bestaandartikel);
                $em->flush();
                return $this->redirect($this->generateurl("alleartikelen"));
            }

            return new Response($this->render('pages/form_wijzigen_artikel_inkoper.html.twig', array('form' => $form->createView())));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/inkoper/artikel/verwijder/{artikelnummer}", name="artikelverwijderen")
     */
    public function verwijderArtikel($artikelnummer)
    {
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $em = $this->getDoctrine()->getEntityManager();
            $adminentities = $em->getRepository('AppBundle:artikel')->find($artikelnummer);

            $adminentities->setActief(0);
            $em->flush();

            return $this->redirect($this->generateurl("alleartikelen"));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/inkoper/artikel/{artikelnummer}", name="artikel")
     */
    public function getArtikel($artikelnummer){
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $artikel = $this->getDoctrine()->getRepository("AppBundle:artikel")->find($artikelnummer);
            return new Response($this->render('pages/artikel_overzicht_inkoper.html.twig', array('artikel' => $artikel)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/inkoper/zoek", name="zoekArtikel")
     */
    public function liveSearchAction(Request $request)
    {
        $session = $this->get('session');
        if ($session->get('rol') == 1 || $session->get('rol') == 2 || $session->get('rol') == 3) {
            //ophalen post data van ajax call
            $string = $_POST['searchText'];
            //maken select statement om alle artikelnummers op te halen
            $em = $this->getDoctrine()->getManager();
            $RAW_QUERY = "SELECT * FROM artikel WHERE artikelnummer LIKE '%".$string."%' OR omschrijving LIKE '%".$string."%'";
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
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/magazijn/artikelen", name="alleartikelenmazijn")
     */
    public function alleArtikelenMagazijn(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 2) {
            //alle artikelen omhalen
            $artikelen = $this->getDoctrine()->getRepository("AppBundle:artikel")->findBy(array('actief' => 1 ));
            //wegschrijven naar html bestand met de artikelen variable
            return new Response($this->render('pages/alle_artikellen_magazijn.html.twig', array('artikelen' => $artikelen)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/magazijn/artikel/{artikelnummer}", name="artikelMagazijn")
     */
    public function getArtikelMagazijn($artikelnummer){
        $session = $this->get('session');
        if ($session->get('rol') == 2) {
            $artikel = $this->getDoctrine()->getRepository("AppBundle:artikel")->find($artikelnummer);
            return new Response($this->render('pages/artikel_overzicht_magazijn.html.twig', array('artikel' => $artikel)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/magazijn/artikel/wijzig/{artikelnummer}", name="artikelwijzigenmagazijn")
     */
    public function wijzigArtikelMagazijn(Request $request, $artikelnummer) {
        $session = $this->get('session');
        if ($session->get('rol') == 2) {
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
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/inkoper/artikelen/inactief", name="alleartikeleninactief")
     */
    public function alleArtikelenInactief(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 1 || $session->get('rol') == 2) {
            //alle artikelen omhalen
            $artikelen = $this->getDoctrine()->getRepository("AppBundle:artikel")->findBy(array('actief' => 0 ));
            //wegschrijven naar html bestand met de artikelen variable
            return new Response($this->render('pages/alle_artikellen_inkoper.html.twig', array('artikelen' => $artikelen)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/inkoper/artikel/actief/{artikelnummer}", name="artikelActief")
     */
    public function artikelActief($artikelnummer)
    {
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $em = $this->getDoctrine()->getEntityManager();
            $adminentities = $em->getRepository('AppBundle:artikel')->find($artikelnummer);
            $adminentities->setActief(1);
            $em->flush();
            return $this->redirect($this->generateurl("alleartikelen"));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/inkoper/artikelen/tebestellen", name="alleartikelentebestellen")
     */
    public function alleArtikelenTeBestellen(){
        $session = $this->get('session');
        if ($session->get('rol') == 1 || $session->get('rol') == 2) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:artikel');
            $query = $repository->createQueryBuilder('p')->where('p.minimumVoorraad > p.vooraad')->getQuery();
            $artikelen = $query->getResult();
            return new Response($this->render('pages/alle_artikellen_bestellen.html.twig', array('artikelen' => $artikelen)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/inkoper/producttype", name="alleaproducttype")
     */
    public function alleProducttype(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 1) {

            $repository = $this->getDoctrine()->getRepository('AppBundle:artikel');
            $query = $repository->createQueryBuilder('p')->groupBy('p.bestelserie')->getQuery();
            $artikelen = $query->getResult();


            $yourCount = $repository->createQueryBuilder('p')->select('sum(p.vooraad) as bestelserieCount, p.bestelserie')
              ->groupBy('p.bestelserie')
              ->getQuery()
              ->getResult();

            return new Response($this->render('pages/producttype.overzicht_inkoper.html.twig', array('artikelen' => $artikelen, 'aantallen' =>  $yourCount)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }
    /**
     * @Route("/inkoper/producttype/{bestelserie}", name="producttype")
     */
    public function getProducttype($bestelserie){
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $artikel = $this->getDoctrine()->getRepository("AppBundle:artikel")->findBy(array("bestelserie" => $bestelserie ));
            return new Response($this->render('pages/alle_producttypen_inkoper.html.twig', array('artikelen' => $artikel)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/inkoper/producttype-tebestellen", name="producttypetebestellen")
     */
    public function producttypeTeBestellen(){
        $session = $this->get('session');
        if ($session->get('rol') == 1 || $session->get('rol') == 2) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:artikel');
            $query = $repository->createQueryBuilder('p')->where('p.minimumVoorraad > p.vooraad')->groupBy('p.bestelserie')->getQuery();
            $artikelen = $query->getResult();



            return new Response($this->render('pages/producttype.overzicht_tebestellen.html.twig', array('artikelen' => $artikelen)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/inkoper/producttype-tebestellen/{bestelserie}", name="alleartikelentebestellenbestelserie")
     */
    public function alleArtikelenTeBestellenbestelserie($bestelserie){
        $session = $this->get('session');
        if ($session->get('rol') == 1 || $session->get('rol') == 2) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:artikel');
            $query = $repository->createQueryBuilder('p')->where('p.minimumVoorraad > p.vooraad AND p.bestelserie = \''.$bestelserie.'\'')->getQuery();
            $artikelen = $query->getResult();
            return new Response($this->render('pages/alle_artikellen_onder_voorraad_bestellen.html.twig', array('artikelen' => $artikelen)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

}
