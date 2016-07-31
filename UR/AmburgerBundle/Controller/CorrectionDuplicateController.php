<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CorrectionDuplicateController extends Controller
{
    public function indexAction($OID)
    {
        return $this->render('AmburgerBundle:DataCorrection:duplicate_persons.html.twig');
    }
    
    public function loadAction($OID)
    {
        // return json response
        
        return new Response("{duplicate_persons: []}");
    }
}
