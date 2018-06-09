<?php

namespace WCPC2K18\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use WCPC2K18Bundle\Entity\Game;
/**
 * La classe LoadTeamData implémente la méthode de chargement initiale des
 * rencontres de la compétition, réalisée à partir d'un fichier JSON. 
 *
 * @author seb
 */
class LoadGameData extends AbstractFixture implements OrderedFixtureInterface {
    
    
    public function load(ObjectManager $manager) {
        
        $datafile = __DIR__ .  "/games_initial.json";
        $games = json_decode(file_get_contents($datafile), true);
        
        foreach ($games as $gameData) {
            $game = new Game();
            $game->setId($gameData['id']);
            $game->setType($gameData['type']);
            $game->setHomeTeam($this->getReference('team-' . $gameData['homeTeam']));
            $game->setAwayTeam($this->getReference('team-' . $gameData['awayTeam']));
            $game->setKickoff(\DateTime::createFromFormat("d/m/Y H:i", $gameData['date']));
            $game->setPhase($gameData["phase"]);
            $game->setGroup($gameData["group"]);
            
            $manager->persist($game);
        }
        
        $manager->flush();
       
        
    }
    
    public function getOrder() {
        return 2;
    }
}
