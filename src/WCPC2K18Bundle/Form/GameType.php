<?php

namespace WCPC2K18Bundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\OptionsResolver\OptionsResolver;
use WCPC2K18Bundle\Entity\Game;

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
    public function buildForm(FormBuilderInterface $builder, $options) {
        
        $game = $options['game'];
        
        $builder->add('goalsHome', 'text', [
            'label' => "Buts " . $game->getHomeTeam()->getName(),
            'required' => true
        ])->add('goalsAway', 'text', [
            'label' => "Buts " . $game->getAwayTeam()->getName(),
        ])->add('extraTime', 'checkbox', [
            'label' => "Prolongations ? ",
            'required' => false,
        ])->add('penaltiesHome', 'text', [
            'label' => 'Penalties ' . $game->getHomeTeam()->getName(),
            'required' => false
        ])->add('penaltiesAway', 'text', [
            'label' => 'Penalties ' . $game->getAwayTeam()->getName(),
            'required' => false
        ])->add('submit', 'submit', [
            'label' => "Valider",
        ])->add('cancel', 'submit', [
            'label' => "Annuler"
        ]);

    }
    
    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        
        $resolver->setRequired('game');
        $resolver->setAllowedTypes('game', Game::class);
        
    }
    
    public function getName() {
        return 'game';
    }
}
