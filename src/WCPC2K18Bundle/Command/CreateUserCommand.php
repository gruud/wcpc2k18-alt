<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace WCPC2K18Bundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use WCPC2K18Bundle\Entity\User;

/**
 * Description of CreateUser
 *
 * @author seb
 */
class CreateUserCommand extends ContainerAwareCommand {

    public function configure() {
        $this->setName('wcpc:user:create')
                ->setDescription("Crée un nouvel utilisateur")
                ->addArgument('login', InputArgument::REQUIRED)
                ->addArgument('email', InputArgument::REQUIRED)
                ->addArgument('password', InputArgument::REQUIRED)
                ->addArgument('firstName', InputArgument::REQUIRED)
                ->addArgument('lastName', InputArgument::REQUIRED)
                ->addArgument('department', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        
        $manager = $this->getContainer()->get(('doctrine.orm.entity_manager'));
        
        $user = new User();
        $user->setUsername($input->getArgument('login'));
        $user->setPlainPassword($input->getArgument('password'));
        $user->addRole('ROLE_USER');
        $user->setEmail($input->getArgument('email'));
        $user->setFirstName($input->getArgument('firstName'));
        $user->setLastName($input->getArgument('lastName'));
        $user->setEnabled(true);
        $user->setDepartment($input->getArgument('department'));

        $manager->persist($user);
        $manager->flush();
    }

}
