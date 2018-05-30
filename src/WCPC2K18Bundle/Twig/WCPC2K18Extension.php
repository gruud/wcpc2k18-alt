<?php

namespace WCPC2K18Bundle\Twig;

use WCPC2K18Bundle\Entity\Team;


/**
 * Description of WCPC2K18Extension
 *
 * @author seb
 */
class WCPC2K18Extension extends \Twig_Extension {
    
    
    public function getFilters() {
        return [
            new \Twig_SimpleFilter('flag', [$this, 'flagFilter']),
        ];
    }
    
    public function flagFilter(Team $team, $size) {
        return 'bundles/wcpc2k18/images/flags/' . $size . "/" . mb_strtolower($team->getAbbreviation()) . ".png";
    }
    
}
