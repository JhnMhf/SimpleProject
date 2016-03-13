<?php

namespace UR\DB\NewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function jsonAction($ID)
    {
        $newDBManager = $this->get('doctrine')->getManager('new');
        $person = $newDBManager->getRepository('NewBundle:Person')->findById($ID);
        
        $serializer = $this->container->get('serializer');
        $json = $serializer->serialize($person, 'json');
        $response = new JsonResponse();
        $response->setContent($json);
        return $response;
    }
}
