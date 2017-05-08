<?php
//Namespace en uses, mag je vergeten. Moet er wel in staan!
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\klant;
use AppBundle\Form\Type\KlantType;

class KlantController extends Controller
{
    /**
     * @Route("/klant/nieuw", name="nieuweklant")
     */
    public function nieuweKlant(Request $request) {
        $nieuweKlant = new klant();
        $form = $this->createForm(KlantType::class, $nieuweKlant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nieuweKlant);
            $em->flush();
            return $this->redirect($this->generateurl("nieuweklant"));
        }

        return new Response($this->render('form.html.twig', array('form' => $form->createView())));
    }

    /**
     * @Route("/klant/wijzig/{klantnummer}", name="klantwijzigen")
     */
    public function wijzigKlant(Request $request, $klantnummer) {
        $bestaandeKlant = $this->getDoctrine()->getRepository("AppBundle:klant")->find($klantnummer);
        $form = $this->createForm(KlantType::class, $bestaandeKlant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bestaandeKlant);
            $em->flush();
            return $this->redirect($this->generateurl("alleklanten"));
        }

        return new Response($this->render('form.html.twig', array('form' => $form->createView())));
    }
}
?>