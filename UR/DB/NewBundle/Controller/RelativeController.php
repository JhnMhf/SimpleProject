<?php

namespace UR\DB\NewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class RelativeController extends Controller
{
    
    public function idAction($ID){
        $relationShipLoader = $this->get('relationship_loader.service');
        
        $relatives = $relationShipLoader->loadRelatives($ID);
        
        $serializer = $this->container->get('serializer');
        $json = $serializer->serialize($relatives, 'json');
        $response = new JsonResponse();
        $response->setContent($json);
        
        return $response;
    }
}
