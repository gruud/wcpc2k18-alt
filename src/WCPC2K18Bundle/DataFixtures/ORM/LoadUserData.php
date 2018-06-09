<?php

namespace WCPC2K18\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WCPC2K18Bundle\Entity\User;


/**
 * La classe LoadUserData implémente la méthode de chargement initiale des utilisateurs
 * de l'application. Cette liste sera créée statiquement à partir des mails
 * d'inscription récoltés.
 *
 * @author Sébastien ZINS
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface {
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $datafile = __DIR__ . "/users.json";
        $users = json_decode(file_get_contents($datafile), true);
        foreach ($users as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setPlainPassword($userData['password']);
            $user->addRole($userData['role']);
            $user->setEmail($userData['email']);
            $user->setFirstName($userData['firstName']);
            $user->setLastName($userData['lastName']);
            $user->setEnabled(true);
            
            $manager->persist($user);
        }
        
        $manager->flush();
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 3;
    }

}
