<?php

namespace AppBundle\Controller;


use AppBundle\Entity\artikel;
use AppBundle\Entity\orderdetails;
use AppBundle\Form\Type\OrderDetailsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;;

use AppBundle\Entity\orders;
use AppBundle\Form\Type\OrderType;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\ordercontrole;




class OrderController extends Controller
{
    /**
     * @Route("/order/nieuw", name="nieuworder")
     */
    public function nieuworder(Request $request) {
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $nieuworder = new orders();
            $form = $this->createForm(OrderType::class, $nieuworder);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($nieuworder);
                $em->flush();
                return $this->redirect($this->generateurl('order', array('ordernummer' => $nieuworder->getId())));
            }
            return new Response($this->render('order/form_nieuw_order.html.twig', array('form' => $form->createView())));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/alle", name="alleorders")
     */
    public function alleOrders(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => array(1, 2, 3, 4)), array('ontvangen' => 'DESC'));
            return new Response($this->render('order/alle_orders.html.twig', array('orders' => $orders)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/alleconcept", name="alleordersconcept")
     */
    public function alleOrdersConcept(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => array(1)), array('ontvangen' => 'DESC'));
            return new Response($this->render('order/alle_orders.html.twig', array('orders' => $orders)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/allebesteld", name="alleordersbesteld")
     */
    public function alleOrdersBesteld(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 1 || $session->get('rol') == 2) {
            $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => array(2)), array('ontvangen' => 'DESC'));
            return new Response($this->render('order/alle_orders.html.twig', array('orders' => $orders)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/alleontvagen", name="alleordersontvangen")
     */
    public function alleOrdersOntvangen(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => array(3)), array('ontvangen' => 'DESC'));
            return new Response($this->render('order/alle_orders.html.twig', array('orders' => $orders)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/magazijn/order/alle", name="alleordersmagazijn")
     */
    public function alleOrdersMagazijn(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 2) {
            $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => 2));
            return new Response($this->render('order/alle_orders_magazijn.html.twig', array('orders' => $orders)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/{ordernummer}", name="order")
     */
    public function getArtikel(Request $request, $ordernummer){
        $session = $this->get('session');
        if ($session->get('rol') == 1 || $session->get('rol') == 2) {
            $nieuworderdetails = new orderdetails();
            $form = $this->createForm(OrderDetailsType::class, $nieuworderdetails, array('ordernummer' => $ordernummer));

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($nieuworderdetails);
                $em->flush();
                return $this->redirect($this->generateURL('order', array('ordernummer'=>$ordernummer)));
            }

            $order = $this->getDoctrine()->getRepository("AppBundle:orders")->find($ordernummer);
            $orderdetails = $this->getDoctrine()->getRepository("AppBundle:orderdetails")->findBy(array('orderId' => $ordernummer));
            return new Response($this->render('order/order_details.html.twig', array('order' => $order,'orderdetails' => $orderdetails, 'form' => $form->createView())));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/magazijn/order/{ordernummer}", name="ordermagazijn")
     */
    public function getArtikelMagazijn(Request $request, $ordernummer){
        $session = $this->get('session');
        if ($session->get('rol') == 2) {
            $order = $this->getDoctrine()->getRepository("AppBundle:orders")->find($ordernummer);
            $orderdetails = $this->getDoctrine()->getRepository("AppBundle:orderdetails")->findBy(array('orderId' => $ordernummer));
            return new Response($this->render('order/order_details_magazijn.html.twig', array('order' => $order,'orderdetails' => $orderdetails)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/naarbesteld/{ordernummer}", name="naarbesteld")
     */
    public function verwijderArtikel($ordernummer)
    {
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $em = $this->getDoctrine()->getEntityManager();
            $adminentities = $em->getRepository('AppBundle:orders')->find($ordernummer);
            $adminentities->setStatus(2);
            $em->flush();
            return $this->redirect($this->generateurl("alleorders"));
        }
        else{
            return new Response('Geen toegang.');
        }
    }


    /**
     * @Route("magazijn/order/controle/{ordernummer}", name="controlebestelling")
     */
    public function controlleBestelling($ordernummer)
    {
        $session = $this->get('session');
        if ($session->get('rol') == 2) {
            $order = $this->getDoctrine()->getRepository("AppBundle:orders")->find($ordernummer);
            $orderdetails = $this->getDoctrine()->getRepository("AppBundle:orderdetails")->findBy(array('orderId' => $ordernummer));
            return new Response($this->render('order/order_controle_magazijn.html.twig', array('order' => $order,'orderdetails' => $orderdetails)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }


    /**
     * @Route("magazijn/order/controlevalidation/{ordernummer}", name="controlevalidationbestelling")
     */
    public function controllevalidationBestelling($ordernummer)
    {
        $session = $this->get('session');
        if ($session->get('rol') == 2) {
            //voorrraad
            foreach($_POST['aantal'] as $aantal){
                //array key (artikelnummer) naar var
                $artikelnummer = key($aantal);
                //ophalen entity artikel
                $em = $this->getDoctrine()->getEntityManager();
                $artikel = $em->getRepository('AppBundle:artikel')->find($artikelnummer);
                //huidige voorraad naar var
                $huidigevooraad = $artikel->getVooraad();
                //huidige voorraad + nieuwe bestelling
                $nieuwevoorraad = $aantal[key($aantal)] + $huidigevooraad;
                //Update voorraad
                $artikel->setVooraad($nieuwevoorraad);
                $em->flush();
            }

            //ontvangstdatum
            $ontvangstdatum = $_POST['datum'];
            $em = $this->getDoctrine()->getEntityManager();
            $order = $em->getRepository('AppBundle:orders')->find($ordernummer);
            $order->setDatumontvangst(new \DateTime($ontvangstdatum));
            $em->flush();

            //status + ontvangen (is dat nog nodig met status?
            $em = $this->getDoctrine()->getEntityManager();
            $order = $em->getRepository('AppBundle:orders')->find($ordernummer);
            $order->setStatus(3);
            $order->setOntvangen(1);
            $em->flush();

            return $this->redirect($this->generateurl("orderKwaliteit", array('ordernummer' => $ordernummer)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/magazijn/order/kwaliteit/{ordernummer}", name="orderKwaliteit")
     */
    public function orderKwaliteit($ordernummer){
        $em = $this->getDoctrine()->getEntityManager();
        $order = $em->getRepository('AppBundle:orders')->find($ordernummer);
        $orderdetails = $em->getRepository('AppBundle:orderdetails')->findBy(array('orderId' => $ordernummer));

        foreach ($orderdetails as $orderdetail){
            $number = $orderdetail->getAantal();
            for ($x = 0; $x < $number; $x++) {
                //print_r($orderdetail->getArtikelnummer()->getArtikelnummer()."<br />");
                $artikelnummers[] = $orderdetail->getArtikelnummer()->getArtikelnummer();
            }
        }

        return new Response($this->render('order/order_kwaliteit.html.twig', array('orders' => $artikelnummers,'ordernummer' => $ordernummer, 'order' => $order)));
    }

    /**
     * @Route("/magazijn/order/kwaliteit/validation/{ordernummer}", name="orderKwaliteitvalidation")
     */
    public function orderkwaltiteitvalidation($ordernummer){
        //kwaliteit
        foreach($_POST['kwalitijd'] as $aantal){
            $kwaliteit = $aantal[key($aantal)];
            $artnummer = key($aantal);


            $order = new ordercontrole();
            $order->setOrderdetailid($artnummer);
            $order->setKwaliteit($kwaliteit);

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
        }

        return $this->redirect($this->generateurl("alleordersmagazijn"));

    }


    /**
     * @Route("/order/alle/datum/zoek", name="alleordersdatumzoek")
     */
    public function alleOrdersDatumzoek(){
        $session = $this->get('session');
        if ($session->get('rol') == 2) {
            return new Response($this->render('order/order_zoek_datum.html.twig'));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/alle/datum", name="alleordersdatum")
     */
    public function alleOrdersDatum(){
        $session = $this->get('session');
        if ($session->get('rol') == 2) {
            $datum = $_POST['jaar'] . '-' . $_POST['maand'] . '-' . $_POST['dag'];
            $datum = new \DateTime($datum);

            $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('leverdatum' => $datum));
            return new Response($this->render('order/alle_orders_zoek.html.twig', array('orders' => $orders)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/alle/status/a-z", name="alleordersOrderbyStatusAZ")
     */
    public function alleordersOrderbyStatusAZ(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => array(1, 2, 3, 4)), array('status' => 'ASC'));
            return new Response($this->render('order/alle_orders.html.twig', array('orders' => $orders)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/alle/status/z-a", name="alleordersOrderbyStatusZA")
     */
    public function alleordersOrderbyStatusZA(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => array(1, 2, 3, 4)), array('status' => 'DESC'));
            return new Response($this->render('order/alle_orders.html.twig', array('orders' => $orders)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/alle/datumontvangst/a-z", name="alleordersOrderbyDatumontvangstAZ")
     */
    public function alleordersOrderbyDatumontvangstAZ(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => array(1, 2, 3, 4)), array('datumontvangst' => 'ASC'));
            return new Response($this->render('order/alle_orders.html.twig', array('orders' => $orders)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/alle/datumontvangst/z-a", name="alleordersOrderbyDatumontvangstZA")
     */
    public function alleordersOrderbyDatumontvangstZA(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => array(1, 2, 3, 4)), array('datumontvangst' => 'DESC'));
            return new Response($this->render('order/alle_orders.html.twig', array('orders' => $orders)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/alle/Leverdatum/a-z", name="alleordersOrderbyLeverdatumAZ")
     */
    public function alleordersOrderbyLeverdatumtAZ(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => array(1, 2, 3, 4)), array('leverdatum' => 'ASC'));
            return new Response($this->render('order/alle_orders.html.twig', array('orders' => $orders)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/alle/Leverdatum/z-a", name="alleordersOrderbyLeverdatumZA")
     */
    public function alleordersOrderbyLeverdatumtZA(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => array(1, 2, 3, 4)), array('leverdatum' => 'DESC'));
            return new Response($this->render('order/alle_orders.html.twig', array('orders' => $orders)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/nieuw/onder/voorraad/{artikelnummer}", name="nieuworderOnderVoorraad")
     */
    public function nieuworderOnderVoorraad(Request $request, $artikelnummer) {
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            if (isset($_POST['submit'])){
                $aantal = $_POST['aantal'];
                $session->set('aantal', $aantal);
            }

            $nieuworder = new orders();
            $form = $this->createForm(OrderType::class, $nieuworder);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($nieuworder);
                $em->flush();

                $orderdetail = new orderdetails();
                $orderdetail->setOrderId($nieuworder->getId());
                $artikel = $this->getDoctrine()->getRepository("AppBundle:artikel")->find($artikelnummer);
                $orderdetail->setArtikelnummer($artikel);
                $orderdetail->setAantal($session->get('aantal'));

                $session->remove('aantal');

                $em = $this->getDoctrine()->getManager();
                $em->persist($orderdetail);
                $em->flush();

                return $this->redirect($this->generateurl('order', array('ordernummer' => $nieuworder->getId())));
            }
            return new Response($this->render('order/form_nieuw_order.html.twig', array('form' => $form->createView())));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/bestellen/{ordernummer}/{artikelnummer}", name="orderEenOrder")
     */
    public function orderEenOrder(Request $request, $ordernummer, $artikelnummer){
        $session = $this->get('session');
        if ($session->get('rol') == 1 || $session->get('rol') == 2) {


            $order = $this->getDoctrine()->getRepository("AppBundle:orders")->find($ordernummer);
            $orderdetails = $this->getDoctrine()->getRepository("AppBundle:orderdetails")->findBy(array('orderId' => $ordernummer));
            return new Response($this->render('order/order_details_een_order.html.twig', array('order' => $order,'orderdetails' => $orderdetails)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/order/retour/{ordernummer}", name="orderRetour")
     */
    public function orderRetour($ordernummer)
    {
        $session = $this->get('session');
        if ($session->get('rol') == 1) {
            $em = $this->getDoctrine()->getEntityManager();
            $adminentities = $em->getRepository('AppBundle:orders')->find($ordernummer);
            $orderdetails = $this->getDoctrine()->getRepository("AppBundle:orderdetails")->findBy(array('orderId' => $ordernummer));



            foreach ($orderdetails as $orderdetail){
                $nieuwevoorraad = $orderdetail->getArtikelnummer()->getVooraad() - $orderdetail->getAantal();
                $orderdetail->getArtikelnummer()->setVooraad($nieuwevoorraad);
            }
            $adminentities->setStatus(4);
            $em->flush();
            return $this->redirect($this->generateurl("alleorders"));
        }
        else{
            return new Response('Geen toegang.');
        }
    }





}
