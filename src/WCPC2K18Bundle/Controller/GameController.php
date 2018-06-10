<?php


namespace WCPC2K18Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


/**
 * La classe GameController implémente le contrôleur prenant en charge la page
 * des rencontres de la compétition. Cette page présente la liste complète des
 * matches, avec la possibilité de consulter le détail pour chacun d'entre eux.
 *
 * @author Sébastien ZINS
 */
class GameController extends Controller {
    //put your code here
    
    public function listAction() {
        
        $manager = $this->getDoctrine()->getManager();
        $games = $manager->getRepository('WCPC2K18Bundle:Game')->findAllWithTeams();
        $predictionChecker = $this->get('wcpc2k18.prediction_checker');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userPredictions = $manager
                ->getRepository('WCPC2K18Bundle:Prediction')
                ->findUserPredictionsIndexedByGameId($user);
        
        return  $this->render('WCPC2K18Bundle:Game:games_list.html.twig', [
            "games" => $games,
            "predictionChecker" => $predictionChecker,
            "userPredictions" => $userPredictions,
            
        ]);
    }

}
