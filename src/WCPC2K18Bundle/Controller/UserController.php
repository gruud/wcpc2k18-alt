<?php

namespace WCPC2K18Bundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
/**
 * La classe UserController implémente les contrôleurs ayant lien avec les utilisateurs
 * de l'application (liste des utilisateurs, profil, etc.)
 *
 * @author Sébastien ZINS
 */
class UserController extends Controller {
    
    /**
     * Contrôleur permettant l'affichage de la liste des rencontres
     */
    public function listAction() {
        return $this->render('WCPC2K18Bundle:User:users_list.html.twig');
    }
    
}
