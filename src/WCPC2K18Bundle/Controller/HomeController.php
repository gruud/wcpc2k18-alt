<?php

namespace WCPC2K18Bundle\Controller;


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
     */
    public function indexAction() {
        return new \Symfony\Component\HttpFoundation\Response("Hello WCPC2K18!");
    }
}
