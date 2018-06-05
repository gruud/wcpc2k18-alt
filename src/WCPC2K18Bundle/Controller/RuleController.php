<?php 

namespace WCPC2K18Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class RuleController extends Controller {



    public function indexAction() {

        return $this->render('WCPC2K18Bundle:Rule:index.html.twig');
    }
}