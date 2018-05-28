<?php

namespace WCPC2K18Bundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * La classe Utilisateur implémente les utilisateurs de l'application de pronostics.
 *
 * @author Sébastien ZINS
 * 
 * @ORM\Entity
 * @ORM\Table("wcpc_users")
 */
class User extends BaseUser{
    
    
    /**
     * @var integer L'identifiant unique de l'utilisateur
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * Constructeur
     */
    public function __construct() {
        parent::__construct();
    }
}
