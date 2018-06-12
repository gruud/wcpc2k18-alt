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
        $trendsData = $this->getPredictionTrendsChartData($game, $predictions, $dataPoolMinSize);

        
       
        return $this->render("WCPC2K18Bundle:Game:game.html.twig", [
            "game" => $game,
            "predictions" => $predictions,
            "predictionPoolMinSize" => $dataPoolMinSize,
            "trendsData" => $trendsData,
        ]);
    }

    /**
     * Récupère les données constitutives du graphique de tendances des pronostics
     * sous la forme d'un objet JSON à injecter directement dans le JS
     * @param array | Prediction[] $predictions La liste des pronostics
     */
    public function getPredictionTrendsChartData(Game $game, $predictions, $dataPoolMinSize) {
        $data = [
            "labels" => [
                "Victoire " . $game->getHomeTeam()->getName() . "(%)",
                "Victoire " . $game->getAwayTeam()->getName() . "(%)",
                "Match nul (%)" 
            ] 
        ];
        
        $predictionsCount = count($predictions);
        
        if ($predictionsCount  >= $dataPoolMinSize) {
            
            /* @var $prediction Prediction */
            $homeWinCount = 0;
            $awayWinCount = 0;
            $drawCount = 0;
            
            foreach ($predictions as $prediction) {
                switch($prediction->getResult()) {
                    case Game::RESULT_WINNER_HOME: {
                        $homeWinCount++;
                        break;
                    }
                    
                    case Game::RESULT_WINNER_AWAY: {
                        $awayWinCount++;
                        break;
                    }
                    
                    case Game::RESULT_DRAW: {
                        $drawCount++;
                        break;
                    }
                }
            }
            
            $data["datasets"] = [[ 
                'data' => [
                    number_format($this->getPercentage($homeWinCount, $predictionsCount), 2, ".",""),
                    number_format($this->getPercentage($awayWinCount, $predictionsCount), 2, ".", ""),
                    number_format($this->getPercentage($drawCount, $predictionsCount), 2, ".", ""),
                ],
                
            ]];
        }
        
        return json_encode($data);
    }
    
    private function getPercentage($number, $total) {
        return $number * 100 / $total;
    } 
}
