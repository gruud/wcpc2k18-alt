<?php

namespace WCPC2K18Bundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * La classe ComputeLeaderBoardCommand implémente la commande d'initialisation
 * ou de réinitialisation du classement des joueurs.
 *
 * @author sebastienzins
 */
class InitLeaderBoardCommand extends ContainerAwareCommand {
    
    /**
     * {@inheritDoc}
     */
    public function configure() {
        $this->setName('wcpc:leaderboard:init')
                ->setDescription("Initialisation du classement joueur");
    }
    
    public function execute(InputInterface $input, OutputInterface $output) {
        
        $output->writeln("<info>Réinitialisation du classement ...");
        $this->getContainer()->get('wcpc2k18.leaderboard_manager')->initLeaderboard();
        
    }
}
