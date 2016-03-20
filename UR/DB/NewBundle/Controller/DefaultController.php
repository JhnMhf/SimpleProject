<?php

namespace UR\DB\NewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function jsonAction($ID)
    {
        $newDBManager = $this->get('doctrine')->getManager('new');
        $person = $newDBManager->getRepository('NewBundle:Person')->findOneById($ID);
        
        if(is_null($person)){
            $person = $newDBManager->getRepository('NewBundle:Relative')->findOneById($ID);
        }
        
        if(is_null($person)){
            $person = $newDBManager->getRepository('NewBundle:Partner')->findOneById($ID);
        }
        
        if(is_null($person)){
           //throw exception
        }

        return $this->get("response_builder.service")->getJSONResponse($person);
    }
}
