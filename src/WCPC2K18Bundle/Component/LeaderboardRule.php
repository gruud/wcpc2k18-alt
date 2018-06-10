<?php

namespace WCPC2K18Bundle\Component;

use Symfony\Component\Yaml\Yaml;

/**
 * La classe  LeaderboardRule implémente le barême des points utilisés pour
 * le calcul des points des rencontres. Il s'appuie sur un fichier rules.yml
 * qui contient les données du barème. 
 *
 * @author sebastienzins
 */
class LeaderboardRule {
    
    
    private $rules;
    

    public static function createRules() {
        $rules = new LeaderboardRule();
        $rules->loadRulesFile();
        
        return $rules;
        
    }
    
    /**
     * Récupère les règles sous la forme d'un tableau. 
     * @return array Les règles sous la forme d'un tableau
     */
    public function toArray() {
        return $this->rules;
    }
    
    /**
     * Charge le fichier barême
     */
    public function loadRulesFile() {
        $ruleFileDir = __DIR__ . "/../Resources/config/rules.yml";
        if (! file_exists($ruleFileDir)) {
            throw new \Exception("Impossible de trouver le fichier de règles " . $ruleFileDir);
        }
        $this->rules = Yaml::parse($ruleFileDir);
    }
}
