<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace WCPC2K18Bundle\Component;

use WCPC2K18Bundle\Entity\Game;
use WCPC2K18Bundle\Entity\Prediction;

/**
 * La classe ChartDataManager implémente le gestionnaire de construction des
 * données à destination des graphes. Il contient des méthodes utilitaires
 * statiques fournissant des données au format JSON pour initialiser les
 * graphes dessinés avec Charts.js
 *
 * @author Sébastien ZINS
 */
class ChartDataManager {

    const PIE_COLOR_HOME = "#5cb95c";
    
    const PIE_COLOR_AWAY = "#f0ad4e";
    
    const PIE_COLOR_DRAW = "#333333";
            
   
    
    /**
     * Récupère les données constitutives du graphique de tendances des pronostics
     * sous la forme d'un objet JSON à injecter directement dans le JS
     * @param array | Prediction[] $predictions La liste des pronostics
     */
    public static function getPredictionTrendsChartData(Game $game, array $predictions, $dataPoolMinSize) {
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
        
            // On utilise ici le fait que le résultat d'une prédiction sort
            // sous la forme d'un entier. On incrémente donc le chiffre correspondant
            // à l'indice de l'entier de résultat. 
            $stats = [0,0,0];
            
            foreach ($predictions as $prediction) {
                $stats[$prediction->getResult()]++;
            }
            
            $data["datasets"] = [[ 
                'data' => [
                    number_format(self::getPercentage($stats[Game::RESULT_WINNER_HOME], $predictionsCount), 2, ".",""),
                    number_format(self::getPercentage($stats[Game::RESULT_WINNER_AWAY], $predictionsCount), 2, ".", ""),
                    number_format(self::getPercentage($stats[Game::RESULT_DRAW], $predictionsCount), 2, ".", ""),
                ],
                'backgroundColor' => [
                    self::PIE_COLOR_HOME,
                    self::PIE_COLOR_AWAY,
                    self::PIE_COLOR_DRAW
                ]
                
            ]];
        }
        
        return json_encode($data);
    }
    
    private static function getPercentage($number, $total) {
        return $number * 100 / $total;
    }
}
