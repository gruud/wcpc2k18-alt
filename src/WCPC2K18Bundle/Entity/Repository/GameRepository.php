<?php

namespace WCPC2K18Bundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * La classe GameRepository implémente un dépôt avancé pour l'entité Game 
 * et contient des classes de récupération spécifiques des rencontres de 
 * la compétition
 *
 * @author seb
 */
class GameRepository extends EntityRepository {
    
    
    public function findAllWithTeams() {
        $qb = $this->createQueryBuilder('g');
        $qb->leftJoin('g.homeTeam', 'ht');
        $qb->leftJoin('g.awayTeam', 'at');
        $qb->addSelect('ht')->addSelect('at');
        
        return $qb->getQuery()->getResult();
    }
}
