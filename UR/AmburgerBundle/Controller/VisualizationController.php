<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Description of UserController
 *
 * @author johanna
 */
class VisualizationController extends Controller{
    
    public function indexAction()
    {
        return $this->render('AmburgerBundle:Visualization:index.html.twig');
    }

    public function detailAction($ID)
    {
        return $this->render('AmburgerBundle:Visualization:detail.html.twig');
    }
    
    public function listOfAllIdsAction(){
        $listOfAllIds = $this->loadAllIds();
        

        $serializer = $this->get('serializer');
        $json = $serializer->serialize($listOfAllIds, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);
        
        return $jsonResponse;
    }
    
    //@TODO: Add caching (also add it for data for the map etc.)
    //http://stackoverflow.com/questions/8893081/how-to-cache-in-symfony-2
    //http://nerdpress.org/2012/07/10/caching-data-in-symfony2/
    private function loadAllIds(){
        $allIds = $this->loadPersonIds();
        
        $allIds = array_merge($allIds, $this->loadRelativeIds());
        
        $allIds = array_merge($allIds, $this->loadPartnerIds());
        
        sort($allIds, SORT_NUMERIC);
        
        return $allIds;
    }
    
    private function loadPersonIds(){
        return $this->runQuery('SELECT id FROM person');
    }
    
    private function loadRelativeIds(){
        return $this->runQuery('SELECT id FROM relative');
    }
    
    private function loadPartnerIds(){
        return $this->runQuery('SELECT id FROM partner');
    }
    
    private function runQuery($sql){
        $finalDBManager = $this->get('doctrine')->getManager('new');

        $stmt = $finalDBManager->getConnection()->prepare($sql);

        $stmt->execute();
        $results = $stmt->fetchAll();
        
        $idArray = [];
        
        for($i = 0; $i < count($results); $i++){
            $idArray[] = $results[$i]['id'];
        }
        
        return $idArray;
    }
}
