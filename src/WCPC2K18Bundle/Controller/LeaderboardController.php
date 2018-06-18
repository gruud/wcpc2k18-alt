<?php


namespace WCPC2K18Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * La classe LeaderboardController implémente les contrôleurs en lien avec 
 * les classements pour la gestion de la compétition.
 *
 * @author Sébastien ZINS
 */
class LeaderboardController extends Controller {
    //put your code here
    
    public function indexAction() {
        
        $leaderboard = $this->getDoctrine()->getManager()
                ->getRepository('WCPC2K18Bundle:Leaderboard')
                ->getFullLeaderboardOrderedForGeneral();
        
        $crews = $this->getDoctrine()->getManager()
                ->getRepository('WCPC2K18Bundle:Crew')
                ->findBy([], ['points' => 'DESC']);
        
        return  $this->render('WCPC2K18Bundle:Leaderboard:leaderboard.html.twig', [
            'leaderboard' => $leaderboard,
            'crews' => $crews
        ]);
    }
}
