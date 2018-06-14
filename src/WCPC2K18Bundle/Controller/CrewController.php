<?php


namespace WCPC2K18Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * La classe CrewController implémente les méthode de contrôleur ayany trait
 * aux équipes de participants
 *
 * @author Sébastien ZINS
 */
class CrewController extends Controller{
    
    
    public function listAction() {
        
        $crews = $this->getDoctrine()->getManager()
                ->getRepository('WCPC2K18Bundle:Crew')
                ->findWithUsers();
        
        return $this->render('WCPC2K18Bundle:Crew:crews_list.html.twig', [
            'crews' => $crews,
        ]);
    }
}
