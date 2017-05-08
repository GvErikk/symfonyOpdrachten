<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\product;
use AppBundle\Form\Type\ProductType;




class ProductController extends Controller
{
    /**
     * @Route("/product/nieuw", name="nieuwproduct")
     */
    public function nieuwproduct(Request $request) {
        $nieuwproduct = new product();
        $form = $this->createForm(ProductType::class, $nieuwproduct);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nieuwproduct);
            $em->flush();
            return $this->redirect($this->generateurl("nieuweklant"));
        }

        return new Response($this->render('form.html.twig', array('form' => $form->createView())));
    }

    /**
     * @Route("/product/wijzig/{barcode}", name="productwijzigen")
     */
    public function wijzigProduct(Request $request, $barcode) {
        $bestaandproduct = $this->getDoctrine()->getRepository("AppBundle:product")->find($barcode);
        $form = $this->createForm(ProductType::class, $bestaandproduct);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bestaandproduct);
            $em->flush();
            return $this->redirect($this->generateurl("alleklanten"));
        }

        return new Response($this->render('form.html.twig', array('form' => $form->createView())));
    }

}
