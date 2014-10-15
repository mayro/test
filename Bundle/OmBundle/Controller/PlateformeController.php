<?php

namespace Adidas\Bundle\OmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PlateformeController extends Controller
{
    public function headerAction()
    {
        require_once __DIR__. '/../../../../../standalone/template.inc.php';
        return new Response(adi_header($GLOBALS['page']));
    }

    public function footerAction()
    {
        require_once __DIR__. '/../../../../../standalone/template.inc.php';
        return new Response(adi_footer($GLOBALS['page']));
    }
    
    public function  participAction()
    {
        return $this->render('AdidaOmBundle:Challenge:challengeParticip.html.twig');
    }
}
