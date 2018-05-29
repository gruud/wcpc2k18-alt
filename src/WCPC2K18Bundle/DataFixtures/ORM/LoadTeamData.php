<?php

namespace WCPC2K18\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use WCPC2K18Bundle\Entity\Team;
/**
 * Description of LoadTeamData
 *
 * @author seb
 */
class LoadTeamData extends AbstractFixture implements OrderedFixtureInterface {
    
    
    public function load(ObjectManager $manager) {
        
        $datafile = __DIR__ .  "/teams.json";
        $teams = json_decode(file_get_contents($datafile), true);
        
        foreach ($teams as $teamData) {
            $team = new Team();
            $team->setId($teamData["id"]);
            $team->setName($teamData["name"]);
            $team->setAbbreviation($teamData["abbr"]);
            
            $this->addReference("team-" . $team->getId(), $team);
            
            $manager->persist($team);
        }
        
        $manager->flush();
    }
    
    public function getOrder() {
        return 1;
    }
}
