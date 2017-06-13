<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\UserType;
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
                    return $this->redirect($this->generateurl("alleartikelen"));
                }
                elseif ($session->get('rol') == 2){
                    return $this->redirect($this->generateurl("alleartikelenmazijn"));
                }
                elseif ($session->get('rol') == 3){
                    return $this->redirect($this->generateurl("alleartikelenverkoper"));
                }
                elseif ($session->get('rol') == 4){
                    return $this->redirect($this->generateurl("allegebruikers"));
                }
                elseif ($session->get('rol') == 5){
                    return $this->redirect($this->generateurl("financieel"));
                }

                //return new Response($session->get('rol'));
            }
        }
        else{
            return new Response($this->render('login/login.html.twig'));
        }

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request)
    {
        $session = $this->get('session');
        $session->remove('gebruikersnaam');
        $session->remove('rol');
        return new Response($this->render('login/login.html.twig'));
    }

    /**
     * @Route("/loginprocess", name="loginprocess")
     */
    public function loginprocess(Request $request)
    {
        return new Response("Hallo wereld");
    }


    /**
     * @Route("/admin/nieuwegebruiker", name="nieuwegebruiker")
     */
    public function nieuwegebruiker(Request $request) {
        $session = $this->get('session');
        if ($session->get('rol') == 4) {
            //todo: gebruikerrol
            $nieuwegebruiker = new users();
            $form = $this->createForm(UserType::class, $nieuwegebruiker);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($nieuwegebruiker);
                $em->flush();
                return $this->redirect($this->generateurl("nieuwegebruiker"));
            }
            return new Response($this->render('login/nieuwe_gebruiker.html.twig', array('form' => $form->createView())));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

    /**
     * @Route("/admin/gebruiker/wijzig/{gebruikerid}", name="gebruikerwijzigen")
     */
    public function wijzigArtikel(Request $request, $gebruikerid) {
        $session = $this->get('session');
        if ($session->get('rol') == 4) {
            $gebruiker = $this->getDoctrine()->getRepository("AppBundle:users")->find($gebruikerid);
            $form = $this->createForm(UserType::class, $gebruiker);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($gebruiker);
                $em->flush();
                return $this->redirect($this->generateurl("allegebruikers"));
            }

            return new Response($this->render('login/nieuwe_gebruiker.html.twig', array('form' => $form->createView())));
        }
        else{
            return new Response('Geen toegang.');
        }
    }


    /**
     * @Route("/admin/gebruikers", name="allegebruikers")
     */
    public function alleGebruikers(Request $request){
        $session = $this->get('session');
        if ($session->get('rol') == 4) {
            //alle artikelen omhalen
            $gebruikers = $this->getDoctrine()->getRepository("AppBundle:users")->findAll();
            //wegschrijven naar html bestand met de artikelen variable
            return new Response($this->render('login/alle_gebruikers.html.twig', array('gebruikers' => $gebruikers)));
        }
        else{
            return new Response('Geen toegang.');
        }
    }

}
