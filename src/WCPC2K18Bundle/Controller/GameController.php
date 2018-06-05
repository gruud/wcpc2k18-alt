<?php


namespace WCPC2K18Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


/**
 * La classe GameController implémente le contrôleur prenant en charge la page
 * des rencontres de la compétition. Cette page présente la liste complète des
 * matches, avec la possibilité de consulter le détail pour chacun d'entre eux.
 *
 * @author Sébastien ZINS
 */
class GameController extends Controller {
    //put your code here
    
    public function listAction() {
        
        $manager = $this->getDoctrine()->getManager();
        $games = $manager->getRepository('WCPC2K18Bundle:Game')->findAllWithTeams();
        
        return  $this->render('WCPC2K18Bundle:Game:games_list.html.twig', [
            "games" => $games,
        ]);
    }

    /**
     * Contrôleur permettant la saisie ou la modufication du résultat pour une rencontre. 
     * La saisie ou la modification du résultat déclenche un recalcul des classements. 
     * 
     * @param integer $gameId L'identifiant de la rencontre pour laquelle on souhaite 
     * remplir le résultat
     * @return Response La réponse à renvoyer au client.
     */
    public function resultAction($gameId) {

        $manager = $this->getDoctrine()->getManager();
        $game = $manager->getRepository('WCPC2K18Bundle:Game')->findOne($gameId);

        if (null === $game) {
            
        }
    }
}
