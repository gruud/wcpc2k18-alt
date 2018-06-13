<?php

namespace WCPC2K18Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use WCPC2K18Bundle\Entity\Game;
use WCPC2K18Bundle\Entity\Prediction;

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

        return $this->render('WCPC2K18Bundle:Game:games_list.html.twig', [
                    "games" => $games,
                    "predictionChecker" => $predictionChecker,
                    "userPredictions" => $userPredictions,
        ]);
    }

    /**
     * Méthode de contrôleur prenant en charge l'affichage du résultat de la rencontre
     * 
     * @param integer $gameId L'identifiant de la rencontre
     */
    public function showAction($gameId) {

        $manager = $this->getDoctrine()->getManager();

        //Récupération de la rencontre
        $game = $manager->getRepository('WCPC2K18Bundle:Game')->find($gameId);
        if (null === $game) {
            throw $this->createNotFoundException();
        }

        //Récupération des prédictions pour la rencontre
        $predictions = $manager->getRepository('WCPC2K18Bundle:Prediction')
                ->findPredictionsForGame($game);
        
        $dataPoolMinSize = $this->getParameter('prediction_trends_min_datapool_size');
        
        $chartManager = $this->get('wcpc2k18.chart_manager');
        $trendsData = $chartManager::getPredictionTrendsChartData($game, $predictions, $dataPoolMinSize);

        
       
        return $this->render("WCPC2K18Bundle:Game:game.html.twig", [
            "game" => $game,
            "predictions" => $predictions,
            "predictionPoolMinSize" => $dataPoolMinSize,
            "trendsData" => $trendsData,
        ]);
    }

     
}
