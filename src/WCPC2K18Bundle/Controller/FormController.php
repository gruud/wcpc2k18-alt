<?php

namespace WCPC2K18Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WCPC2K18Bundle\Entity\Prediction;
use WCPC2K18Bundle\Entity\Game;

/**
 * La classe FormController prend en charge les contrôleurs chargés d'afficher
 * et de valider les différents formulaires du site. 
 *
 * @author Sébastien ZINS
 */
class FormController extends Controller {
    
    /**
     * Contrôleur chargé d'assurer la saisie et l'enregistrement des pronostics
     * d'un joueur. Le formulaire est unique pour la création initial du pronostic
     * et sa modification. 
     * 
     * @param Request $request L'objet requête construit par Symfony
     * @param string $gameId L'identifiant de la rencontre pour laquelle l'utilisateur
     * saisit un pronostic
     * 
     * @return Response L'objet Response à renvoyer au client
     */
    public function predictionAction(Request $request, $gameId) {

        //1. Récupération de l'utilisateur et vérification de ses droits
        // Note : on ne vérifie pas spécifique les rôles de l'utilisateur. 
        // Le firewall utilisé avec FosUserBundle nous garantit que seul des 
        // utilisateurs connectés pourront accéder à cette page. Comme le rôle
        // de base ROLE_USER suffit, aucune vérification complémentaire n'est
        // nécessaire.
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $predictionChecker = $this->get('wcpc2k18.prediction_checker');
        
        //2. Récupération de la rencontre. Si aucune rencontre n'est trouvé pour
        // l'identifiant, on renvoie une erreur 404.
        $manager = $this->getDoctrine()->getManager();
        $game = $manager->getRepository('WCPC2K18Bundle:Game')->find($gameId);
        
        if ($game === null) {
            throw $this->createNotFoundException();
        }
        
        //3. Récupération du pronostic, s'il existe.
        $prediction = $manager->getRepository('WCPC2K18Bundle:Prediction')
                ->findUserPredictionForGame($user, $game);
        
        if ($prediction === null) {
            $prediction = new Prediction();
            $prediction->setGame($game);
            $prediction->setUser($user);
        }

        
        $form = $this->createForm('prediction', $prediction, compact('user', 'game', 'predictionChecker'));
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            if ($form->isValid() && $form->getClickedButton()->getName() === 'submit') {
                $manager->persist($prediction);
                $manager->flush();
                $this->get('session')->getFlashBag()->add('success', $this->getPredictionSuccessMessage($prediction));
            }
            return $this->redirectToSource($request);
        }
        
        //4. Mise en place du formulaire
        return $this->render('WCPC2K18Bundle:Form:form_prediction.html.twig', [
            'form' => $form->createView(),
            'game' => $game,
        ]);
    }
    
    /**
     * Méthode de contrôleur en charge du formulaire de saisie d'un score de 
     * rencontre. 
     * 
     * @param Request $request La requête Symfony
     */
    public function gameAction(Request $request, $gameId) {
        
        //Seul un utilisateur disposant du rôle d'administrateur peut saisir le 
        //résultat. 
        if (! $this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous ne disposez pas des '
                    . 'droits suffisants pour saisir le résultat de la rencontre');
        }
        
        
        //Récupération de la rencontre
        $manager = $this->getDoctrine()->getManager();
        $game = $manager->getRepository('WCPC2K18Bundle:Game')->find($gameId);
        
        if (null === $game) {
            throw $this->createNotFoundException();
        }
        
        //Initialisation du formulaire
        $form = $this->createForm('game', $game, ['game' => $game]);
        $form->handleRequest($request);
        
        //Traitement du formulaire
        if ($form->isSubmitted()) {
            if ($form->getClickedButton()->getName() == "submit" && $form->isValid()) {
                $this->get('wcpc2k18.leaderboard_manager')->computeLeaderboard();
                $manager->flush();
                $this->get('session')->getFlashBag()->add('success', $this->getGameSuccessMessage($game));

            } 
            return $this->redirectToSource($request);
        }
        
        return $this->render('WCPC2K18Bundle:Form:form_game.html.twig', [
            'form' => $form->createView(),
            'game' => $game
        ]);
        
    }
    
    /**
     * Renvoie un objet Response permettant de rediriger l'utilisateur vers la 
     * source indiquée dans le paramètre 'source' de la requête. Si ce paramètre
     * n'existe pas, l'utilisateur est redirigé vers la page d'accueil.
     * 
     * @param Request $request La requête 
     * @return Response La réponse de redirection
     */
    private function redirectToSource($request) {
        $redirection  =$request->get('source', $this->generateUrl('wcpc2k18_home'));
        return $this->redirect($redirection);
    }
    
    /**
     * Crée un message indiquant que le score de la rencontre a bien été
     * enregistré
     * @param Game $game La rencontre
     * @return string La chaîne générée, prête à être employée dans un message
     * flash
     */
    private function getGameSuccessMessage(Game $game) {
        return "Le score de la rencontre"
        . $game->getHomeTeam()->getName() . " - "
        . $game->getAwayTeam()->getName() . " : "
        . $game->getGoalsHome() . ' - ' . $game->getGoalsAway()
        . " a bien été enregistré";
    }
    
    /**
     * Crée un message indiquant que le pronostic a été pris en compte
     * 
     * @param Prediction $prediction Le pronostic réalisé
     * @return string Une chaîne de caractères. 
     */
    private function getPredictionSuccessMessage(Prediction $prediction) {
        return 'Votre pronostic a bien été enregistré :'
        . $prediction->getGame()->getHomeTeam()->getName() . " - "
        . $prediction->getGame()->getAwayTeam()->getName() . " : "
        . $prediction->getGoalsHome() . ' - ' . $prediction->getGoalsAway();
    }
}
