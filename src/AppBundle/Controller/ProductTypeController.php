<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\producttype;
use AppBundle\Form\Type\ProductTypeType;




class ProductTypeController extends Controller
{
    /**
     * @Route("/producttype/nieuw", name="nieuwproducttype")
     */
    public function nieuwproduct(Request $request) {
        $nieuwproduct = new producttype();
        $form = $this->createForm(ProductTypeType::class, $nieuwproduct);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nieuwproduct);
            $em->flush();
            return $this->redirect($this->generateurl("nieuwproducttype"));
        }

        return new Response($this->render('form.html.twig', array('form' => $form->createView())));
    }

    /**
     * @Route("/producttype/wijzig/{tid}", name="producttypewijzigen")
     */
    public function wijzigProduct(Request $request, $tid) {
        $bestaandproducttype = $this->getDoctrine()->getRepository("AppBundle:producttype")->find($tid);
        $form = $this->createForm(producttypetype::class, $bestaandproducttype);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bestaandproducttype);
            $em->flush();
            return $this->redirect($this->generateurl("alleklanten"));
        }

        return new Response($this->render('form.html.twig', array('form' => $form->createView())));
    }
}
