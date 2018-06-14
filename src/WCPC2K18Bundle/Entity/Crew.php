<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace WCPC2K18Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use WCPC2K18Bundle\Entity\User;

/**
 * La classe Crew implémente une équipe de pronostics
 *
 * @author sebastienzins
 * 
 * @ORM\Entity(repositoryClass="WCPC2K18Bundle\Entity\Repository\CrewRepository")
 * @ORM\Table(name="wcpc_crews")
 */
class Crew {
    
    /**
     *
     * @var integer L'identifiant unique de l'équipe
     * 
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     *
     * @var string Le nom de l'équipe;
     * 
     * @ORM\Column(type="string", length=128)
     */
    private $name;
    

    /**
     *
     * @var double Les points de l'équipe pour le classement général
     * @ORM\Column(type="float", nullable=true)
     */
    private $points;
    
    /**
     *
     * @var ArrayCollection | User[] La liste des utilisateurs de l'équipe
     * 
     * @ORM\OneToMany(targetEntity="User", mappedBy="crew")
     */
    private $users;
    
    
    
    public function __construct() {
        $this->users = new ArrayCollection();
    }
    /**
     * Récupère l'identifiant de l'équipe
     * @return integer L'identifiant de l'équipe
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Récupère le nom de l'équipe
     * @return Le nom de l'équipe
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Positionne le nom de l'équipe
     * @param string $name Le nom à positionner
     */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
     * Récupère les points de l'équipe au classement par équipe
     * @return double Les points de l'équipe
     */
    public function getPoints() {
        return $this->points;
    }

    /**
     * 
     * @param double $points Positionne les points de l'équipe pour le classement
     * par équipe
     */
    public function setPoints($points) {
        $this->points = $points;
    }
    
    /**
     * Récupère la liste des utilisateurs appartenant à l'équipe
     * @return ArrayCollection | User[] La liste des utilisateurs de l'équipe
     */
    public function getUsers() {
        return $this->users;
    }

    /**
     * Positionne la liste des utilisateurs de l'équipe
     * @param ArrayCollection $users Les utilisateurs à lier à l'équipe
     */
    public function setUsers(ArrayCollection $users) {
        $this->users = $users;
    }
    
    /**
     * Ajoute un utilisateur à l'équipe
     * @param User $user L'utilisateur à ajouter
     */
    public function addUser(User $user) {
        $this->users->add($user);
    }
    
    /**
     * Supprime un utiisateur de l'équipe
     * @param User $user L'utilisateur à supprimer
     */
    public function removeUser(User $user) {
        $this->users->removeElement($user);
    }

}
