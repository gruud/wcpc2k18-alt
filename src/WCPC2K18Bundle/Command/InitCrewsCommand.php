<?php


namespace WCPC2K18Bundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use WCPC2K18Bundle\Entity\User;
use WCPC2K18Bundle\Entity\Crew;

/**
 * La classe InitCrewsCommand implémente la commande d'initialisation des entité
 * équipe à partir du champ standard department de l'entité User
 *
 * @author Sébastien ZINS
 */
class InitCrewsCommand extends ContainerAwareCommand {
    
    /**
     * {@inheritDoc}
     */
    public function configure() {
        $this->setName('wcpc:crew:init')
                ->setDescription("Initialise les données des équipes de participants");
    }
    
    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output) {
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $users = $manager->getRepository('WCPC2K18Bundle:User')->findAll();
        
        $output->writeln("<info> - Suppression des équipes existantes...</info>");
        $crews = $manager->getRepository('WCPC2K18Bundle:Crew')->findAll();
        foreach($users as $user) {
            $user->setCrew(null);
        }
        $manager->flush();
        
        foreach($crews as $crew) {
            $manager->remove($crew);
            $manager->flush();
        }
        
        $output->writeln("<info>Création et liaison des nouvelles équipes</info>");
        foreach($users as $user) {
            $output->writeln("<info>    - Recherche de l'équipe pour l'utilisateur $user</info>");
            $crew = $manager->getRepository('WCPC2K18Bundle:Crew')->findOneBy([
                "name" => $user->getDepartment(),
            ]);
            
            if (null === $crew) {
                $crew = new Crew();
                $crew->setName($user->getDepartment());
                $crew->addUser($user);
                $crew->setPoints(0);
                $manager->persist($crew);
                
            }
            $user->setCrew($crew);
            $manager->flush();
        }
    }
}
