<?php
//Namespace en uses, mag je vergeten. Moet er wel in staan!
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class MijnEersteController extends Controller
{
    /**
         * @Route("/hallo/wereld", name="hallo_wereld")
     */
    public function halloWereld() {
        return new Response("Hallo wereld ik ben een Symfony applicatie!");
    }

    /**
     * @Route("/alle/klanten", name="alleklanten")
     */
    public function alleKlanten(Request $request){
        $klanten = $this->getDoctrine()->getRepository("AppBundle:klant")->findAll();
        $tekst = '';
        foreach ($klanten as $klant){
            $tekst .= $klant->getVoornaam() .$klant->getAchternaam() .$klant->getTelefoonnummer(). '<br />';

        }
        return new Response($tekst);
    }

//    /**
//     * @Route("/alle/klanten/{voornaam}", name="klantopvoornaam")
//     */
//    public function klantOpVoornaam(Request $request, $voornaam){
//        $klanten = $this->getDoctrine()->getRepository("AppBundle:klant")->findByVoornaam($voornaam);
//        $tekst = '';
//        foreach ($klanten as $klant){
//            $tekst .= $klant->getVoornaam() .$klant->getAchternaam() .$klant->getTelefoonnummer(). '<br />';
//
//        }
//        return new Response($tekst);
//    }


    /**
     * @Route("/alle/klanten/{woonplaats}", name="klantopwoonplaats")
     */
    public function klantOpWoonplaats(Request $request, $woonplaats){
        $klanten = $this->getDoctrine()->getRepository("AppBundle:klant")->findByWoonplaats ($woonplaats);
        $tekst = '';
        foreach ($klanten as $klant){
            $tekst .= $klant->getVoornaam() .$klant->getAchternaam() .$klant->getTelefoonnummer(). '<br />';

        }
        return new Response($tekst);
    }

}
?>