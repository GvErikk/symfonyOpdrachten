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




class OrderController extends Controller
{
    /**
     * @Route("/order/nieuw", name="nieuworder")
     */
    public function nieuworder(Request $request) {
        $nieuworder = new orders();
        $form = $this->createForm(OrderType::class, $nieuworder);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nieuworder);
            $em->flush();
            return $this->redirect($this->generateurl('order', array('ordernummer'=>$nieuworder->getId())));
        }
        return new Response($this->render('order/form_nieuw_order.html.twig', array('form' => $form->createView())));
    }

    /**
     * @Route("/order/alle", name="alleorders")
     */
    public function alleOrders(Request $request){
        $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => array(1, 2, 3)), array('ontvangen' => 'DESC'));
        return new Response($this->render('order/alle_orders.html.twig', array('orders' => $orders)));
    }

    /**
     * @Route("/order/alleconcept", name="alleordersconcept")
     */
    public function alleOrdersConcept(Request $request){
        $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => array(1)), array('ontvangen' => 'DESC'));
        return new Response($this->render('order/alle_orders.html.twig', array('orders' => $orders)));
    }

    /**
     * @Route("/order/allebesteld", name="alleordersbesteld")
     */
    public function alleOrdersBesteld(Request $request){
        $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => array(2)), array('ontvangen' => 'DESC'));
        return new Response($this->render('order/alle_orders.html.twig', array('orders' => $orders)));
    }

    /**
     * @Route("/order/alleontvagen", name="alleordersontvangen")
     */
    public function alleOrdersOntvangen(Request $request){
        $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => array(3)), array('ontvangen' => 'DESC'));
        return new Response($this->render('order/alle_orders.html.twig', array('orders' => $orders)));
    }

    /**
     * @Route("/magazijn/order/alle", name="alleordersmagazijn")
     */
    public function alleOrdersMagazijn(Request $request){
        $orders = $this->getDoctrine()->getRepository("AppBundle:orders")->findBy(array('status' => 2));
        return new Response($this->render('order/alle_orders_magazijn.html.twig', array('orders' => $orders)));
    }

    /**
     * @Route("/order/{ordernummer}", name="order")
     */
    public function getArtikel(Request $request, $ordernummer){
        $nieuworderdetails = new orderdetails();
        $form = $this->createForm(OrderDetailsType::class, $nieuworderdetails);

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

    /**
     * @Route("/magazijn/order/{ordernummer}", name="ordermagazijn")
     */
    public function getArtikelMagazijn(Request $request, $ordernummer){


        $order = $this->getDoctrine()->getRepository("AppBundle:orders")->find($ordernummer);
        $orderdetails = $this->getDoctrine()->getRepository("AppBundle:orderdetails")->findBy(array('orderId' => $ordernummer));
        return new Response($this->render('order/order_details_magazijn.html.twig', array('order' => $order,'orderdetails' => $orderdetails)));
    }

    /**
     * @Route("/order/naarbesteld/{ordernummer}", name="naarbesteld")
     */
    public function verwijderArtikel($ordernummer)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $adminentities = $em->getRepository('AppBundle:orders')->find($ordernummer);
        $adminentities->setStatus(2);
        $em->flush();
        return $this->redirect($this->generateurl("alleorders"));
    }


    /**
     * @Route("magazijn/order/controle/{ordernummer}", name="controlebestelling")
     */
    public function controlleBestelling($ordernummer)
    {
        $order = $this->getDoctrine()->getRepository("AppBundle:orders")->find($ordernummer);
        $orderdetails = $this->getDoctrine()->getRepository("AppBundle:orderdetails")->findBy(array('orderId' => $ordernummer));
        return new Response($this->render('order/order_controle_magazijn.html.twig', array('order' => $order,'orderdetails' => $orderdetails)));
    }


    /**
     * @Route("magazijn/order/controlevalidation/{ordernummer}", name="controlevalidationbestelling")
     */
    public function controllevalidationBestelling($ordernummer)
    {
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
        //kwaliteit
        foreach($_POST['kwalitijd'] as $aantal){
            //array key (artikelnummer) naar var
            $artikelnummer = key($aantal);
            //ophalen entity artikel
            $em = $this->getDoctrine()->getEntityManager();
            $artikel = $em->getRepository('AppBundle:artikel')->find($artikelnummer);
            //Update voorraad
            $artikel->setKwaliteit($aantal[key($aantal)]);
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

        return $this->redirect($this->generateurl("alleordersmagazijn"));
    }


}
