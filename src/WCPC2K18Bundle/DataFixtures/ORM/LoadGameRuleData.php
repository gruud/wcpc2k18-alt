<?php


namespace WCPC2K18\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WCPC2K18Bundle\Entity\GameRule;

/**
 * La classe LoadGameRuleData implémente le chargement initial des règles du
 * jeu dans la base de données
 *
 * @author sebastienzins
 */
class LoadGameRuleData extends AbstractFixture implements OrderedFixtureInterface {
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $datafile = __DIR__ . "/rules.json";
        $rules = json_decode(file_get_contents($datafile), true);
        
        
        foreach ($rules as $rule) {
            $gameRule = new GameRule();
            $gameRule->setId($rule['id']);
            $gameRule->setLabel($rule['label']);
            $gameRule->setPointsForCorrectWinner($rule['pointsWinner']);
            $gameRule->setPointsForCorrectGA($rule['pointsGA']);
            $gameRule->setPointsForPerfect($rule['pointsPerfect']);
            $gameRule->setJackpotEnabled($rule['jackpot']);
            $gameRule->setJackpotMultiplicator($rule['multiplicator']);
            $manager->persist($gameRule);
            
            $this->addReference('rule-' . $gameRule->getId(), $gameRule);
        }
        
        $manager->flush();
    }

    /**
     * 
     * {@inheritDoc}
     */
    public function getOrder() {
        return 2;
    }

}
