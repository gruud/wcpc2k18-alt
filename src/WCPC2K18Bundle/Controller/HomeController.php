<?php

namespace WCPC2K18Bundle\Controller;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
/**
 * La classe HomeController implémente le contrôleur de la page d'accueil de 
 * l'application, accessible une fois l'utilisateur connecté. 
 *
 * @author Sébastien ZINS
 */
class HomeController extends Controller {
    
    /**
     * Méthode d'entrée du contrôleur pour la route "wcpc2k18_home"
     * 
     * @return Response La réponse à renvoyer au client
     */
    public function indexAction() {
        
        //1. Récupération des prochaines rencontres à pronostiquer, agrémentées des pronostics
        // de l'utilisateur connecté.
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $manager = $this->getDoctrine()->getManager();
        
        $nextGames = $manager->getRepository('WCPC2K18Bundle:Game')->findNextGames($user);
        $predictionChecker = $this->get('wcpc2k18.prediction_checker');
        
        
    return $this->render('WCPC2K18Bundle:Home:home.html.twig', compact('nextGames', 'predictionChecker'));
    }
}
