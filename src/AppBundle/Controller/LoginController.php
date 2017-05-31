<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\users;
use Symfony\Component\HttpFoundation\Session\Session;





class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request)
    {
        if (isset($_POST['verzenden'])){
            $em = $this->getDoctrine()->getEntityManager();
            $user = $em->getRepository('AppBundle:users')->findBy(array('gebruikersnaam' => $_POST['gebruikersnaam']));
            if (!$user){
                return new Response($this->render('login/login.html.twig', array('loginfail' => 1)));
            }
            elseif ($user[0]->getWachtwoord() != $_POST['wachtwoord']){
                return new Response($this->render('login/login.html.twig', array('loginfail' => 2)));
            }
            elseif ($user[0]->getWachtwoord() == $_POST['wachtwoord']){
                $session = new Session();

                $session->set('gebruikersnaam', $user[0]->getGebruikersnaam());
                $session->set('rol', $user[0]->getRol());

                if ($session->get('rol') == 1){
                    //Verkoper
                }
                elseif ($session->get('rol') == 2){
                    //magazijn
                }
                elseif ($session->get('rol') == 3){
                    //leeg
                }

                //return new Response($session->get('rol'));
            }
        }
        else{
            return new Response($this->render('login/login.html.twig'));
        }

    }

    /**
     * @Route("/loginprocess", name="loginprocess")
     */
    public function loginprocess(Request $request)
    {
        return new Response("Hallo wereld ik ben helemaal geen homo");
    }
}
