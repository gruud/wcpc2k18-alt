<?php

namespace WCPC2K18Bundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * La classe GameType implémente le formulaire de saisie du résultat réel 
 * d'une rencontre. Il permet notamment des saisir des données précises
 * sur les scores qui permettront ensuite d'évaluer les pronostics des joueurs
 *
 * @author Sébastien ZINS
 */
class GameType extends AbstractType {
    
    /**
     * Construit le formulaire de saisie du résultat d'une rencontre
     * 
     * @param FormBuilderInterface $builder Le constructeur de formulaire Symfony
     */
    public function buildForm(FormBuilderInterface $builder) {
        
        // Les labels des scores et penalties sont générés dynamiquement en 
        // récupérant le nom des équipes
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }
}
