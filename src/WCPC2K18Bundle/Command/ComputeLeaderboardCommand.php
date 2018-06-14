<?php


namespace WCPC2K18Bundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
/**
 * La classe ComputeLeaderboardCommand implémente la commande de calcule du 
 * classement. Il s'agit d'uen simple coquille pour l'appel du service
 * leaderboardManager
 *
 * @author Sébastien ZINS
 */
class ComputeLeaderboardCommand extends ContainerAwareCommand {
    
    /**
     * {@inheritDoc}
     */
    public function configure() {
        $this->setName('wcpc:leaderboard:compute')
                ->setDescription("Calcule les classements");
    }
    
    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output) {
        $lbManager = $this->getContainer()->get('wcpc2k18.leaderboard_manager');
        $lbManager->computeLeaderboards();
    }
 
}
