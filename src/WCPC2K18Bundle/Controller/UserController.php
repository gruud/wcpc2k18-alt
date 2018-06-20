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
        $users = $this->getDoctrine()->getManager()->getRepository('WCPC2K18Bundle:User')->findBy([], [
            "lastName" => "ASC",
            "firstName" => "ASC",
        ]);
        
        return $this->render('WCPC2K18Bundle:User:users_list.html.twig', [
            'users' => $users
        ]);
    }
    
    public function detailAction($userId) {
        $user = $this->getDoctrine()->getManager()
                ->getRepository('WCPC2K18Bundle:User')
                ->findWithPredictionsOrderedByGameId($userId);
        
        if (null === $user) {
            throw $this->createNotFoundException();
        }
        
        return $this->render('WCPC2K18Bundle:User:user_detail.html.twig', [
            'user' => $user,
        ]);
        
        
    }
    
}
