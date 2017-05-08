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
            return $this->redirect($this->generateurl("nieuwartikel"));
        }

        return new Response($this->render('form_nieuw_artikel_inkoper.html.twig', array('form' => $form->createView())));
    }
}
