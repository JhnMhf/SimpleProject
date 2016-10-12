<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of UserController
 *
 * @author johanna
 */
class VisualizationController extends Controller{
    
    public function indexAction()
    {
        $googleApiKey = $this->container->getParameter('amburger.google_api_key');
        return $this->render('AmburgerBundle:Visualization:index.html.twig', array('google_api_key'=>$googleApiKey));
    }

    public function detailAction($ID)
    {
        $googleApiKey = $this->container->getParameter('amburger.google_api_key');
        return $this->render('AmburgerBundle:Visualization:detail.html.twig', array('google_api_key'=>$googleApiKey));
    }
    
    public function detailLoadAction($ID)
    {
        $person = $this->loadPersonById($ID);
        
        if(is_null($person)){
            $response = new Response();
            $response->setContent("Found no person for this id.");
            $response->setStatusCode("404");
            return $response;
        } else {      
            $serializer = $this->get('serializer');
            $json = $serializer->serialize($person, 'json');
            $jsonResponse = new JsonResponse();
            $jsonResponse->setContent($json);

            return $jsonResponse;
        }
    }
    
    public function detailLocationsAction($ID)
    {
        $locationData = $this->internalLoadLocationsForIds(array($ID));
        $serializer = $this->get('serializer');
        $json = $serializer->serialize($locationData, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);

        return $jsonResponse;
    }
    
    public function detailRelationsAction($ID){
        
        $serializer = $this->get('serializer');
        $relationShipLoader = $this->get('relationship_loader.service');
        $em = $this->get('doctrine')->getManager('final');
        
        $familyTree = $relationShipLoader->loadDataForFamilyTree($em,$ID);
        $json = $serializer->serialize($familyTree, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);

        return $jsonResponse;
    }
    
    private function loadPersonById($ID){
        $finalDBManager = $this->getDBManager();
        $person = $finalDBManager->getRepository('NewBundle:Person')->findOneById($ID);

        if (is_null($person)) {
            $person = $finalDBManager->getRepository('NewBundle:Relative')->findOneById($ID);
        }

        if (is_null($person)) {
            $person = $finalDBManager->getRepository('NewBundle:Partner')->findOneById($ID);
        }

        return $person;
            
    }
    
    public function listOfAllIdsAction(){
        $listOfAllIds = $this->loadAllIds();
        

        $serializer = $this->get('serializer');
        $json = $serializer->serialize($listOfAllIds, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);
        
        return $jsonResponse;
    }
    
    public function loadAllLocationsAction(){
        $serializer = $this->get('serializer');

        $locationData = $this->internalLoadAllLocations();

        $json = $serializer->serialize($locationData, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);

        return $jsonResponse;
    }
    
    public function loadLocationsForIdsAction(){
        $content = $this->get("request")->getContent();
        
        if (!empty($content))
        {
            $serializer = $this->get('serializer');
            
            $personIds = $serializer->deserialize($content, 'array', 'json');
            
            $locationData = $this->internalLoadLocationsForIds($personIds);

            $json = $serializer->serialize($locationData, 'json');
            $jsonResponse = new JsonResponse();
            $jsonResponse->setContent($json);

            return $jsonResponse;
        } else {
            $response = new Response();
            $response->setContent("Missing Content.");
            $response->setStatusCode("406");
            return $response;
        }
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
        $finalDBManager = $this->getDBManager();

        $stmt = $finalDBManager->getConnection()->prepare($sql);

        $stmt->execute();
        $results = $stmt->fetchAll();
        
        $idArray = [];
        
        for($i = 0; $i < count($results); $i++){
            $idArray[] = $results[$i]['id'];
        }
        
        return $idArray;
    }
    
    private function internalLoadAllLocations(){
        $sql = "SELECT id,location_name,latitude, longitude, locationData.sum as count FROM location
                JOIN (SELECT locations.locationid as locationid,SUM(locations.c) as sum
                FROM (
                  SELECT baptism_locationid as locationid,COUNT(*) AS c FROM baptism WHERE baptism_locationid IS NOT NULL GROUP BY baptism_locationid
                  UNION ALL
                  SELECT origin_locationid as locationid,COUNT(*) AS c FROM birth WHERE origin_locationid IS NOT NULL GROUP BY origin_locationid
                  UNION ALL
                  SELECT birth_locationid as locationid,COUNT(*) AS c FROM birth WHERE birth_locationid IS NOT NULL GROUP BY birth_locationid
                  UNION ALL
                  SELECT death_locationid as locationid,COUNT(*) AS c FROM death WHERE death_locationid IS NOT NULL GROUP BY death_locationid
                  UNION ALL
                  SELECT locationid,COUNT(*) AS c FROM education WHERE locationid IS NOT NULL GROUP BY locationid
                  UNION ALL
                  SELECT graduation_locationid as locationid,COUNT(*) AS c FROM education WHERE graduation_locationid IS NOT NULL GROUP BY graduation_locationid
                  UNION ALL
                  SELECT locationid,COUNT(*) AS c FROM honour WHERE locationid IS NOT NULL GROUP BY locationid
                  UNION ALL
                  SELECT locationid,COUNT(*) AS c FROM property WHERE locationid IS NOT NULL GROUP BY locationid
                  UNION ALL
                  SELECT locationid,COUNT(*) AS c FROM rank WHERE locationid IS NOT NULL GROUP BY locationid
                  UNION ALL
                  SELECT residence_locationid as locationid,COUNT(*) AS c FROM residence WHERE residence_locationid IS NOT NULL GROUP BY residence_locationid
                  UNION ALL
                  SELECT locationid,COUNT(*) AS c FROM road_of_life WHERE locationid IS NOT NULL GROUP BY locationid
                  UNION ALL
                  SELECT locationid,COUNT(*) AS c FROM status_information WHERE locationid IS NOT NULL GROUP BY locationid
                  UNION ALL
                  SELECT wedding_locationid as locationid,COUNT(*) AS c FROM wedding WHERE wedding_locationid IS NOT NULL GROUP BY wedding_locationid
                  UNION ALL
                  SELECT locationid,COUNT(*) AS c FROM works WHERE locationid IS NOT NULL GROUP BY locationid
                ) locations
                GROUP BY locationId
                ORDER BY SUM(locations.c) DESC) locationData
                ON location.id = locationData.locationid
                WHERE location.latitude IS NOT NULL AND location.longitude IS NOT NULL";
        
        $finalDBManager = $this->getDBManager();

        $stmt = $finalDBManager->getConnection()->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    private function internalLoadLocationsForIds($personIds){
        $sql = "SELECT id,location_name,latitude, longitude, locationData.sum FROM location
                JOIN (SELECT locations.locationid as locationid,SUM(locations.c) as sum
                FROM (
                  SELECT baptism_locationid as locationid,COUNT(*) AS c FROM baptism WHERE baptism_locationid IS NOT NULL 
                  AND (
                      id IN (SELECT baptism_id FROM person WHERE baptism_id IS NOT NULL AND id IN (?))
                      OR id IN (SELECT baptism_id FROM relative WHERE baptism_id IS NOT NULL AND id IN (?))
                      OR id IN (SELECT baptism_id FROM partner WHERE baptism_id IS NOT NULL AND id IN (?))
                  )
                  GROUP BY baptism_locationid
                  UNION ALL
                  SELECT origin_locationid as locationid,COUNT(*) AS c FROM birth WHERE origin_locationid IS NOT NULL 
                  AND (
                      id IN (SELECT birth_id FROM person WHERE birth_id IS NOT NULL AND id IN (?))
                      OR id IN (SELECT birth_id FROM relative WHERE birth_id IS NOT NULL AND id IN (?))
                      OR id IN (SELECT birth_id FROM partner WHERE birth_id IS NOT NULL AND id IN (?))
                  )
                  GROUP BY origin_locationid
                  UNION ALL
                  SELECT birth_locationid as locationid,COUNT(*) AS c FROM birth WHERE birth_locationid IS NOT NULL 
                  AND (
                      id IN (SELECT birth_id FROM person WHERE birth_id IS NOT NULL AND id IN (?))
                      OR id IN (SELECT birth_id FROM relative WHERE birth_id IS NOT NULL AND id IN (?))
                      OR id IN (SELECT birth_id FROM partner WHERE birth_id IS NOT NULL AND id IN (?))
                  )
                  GROUP BY birth_locationid
                  UNION ALL
                  SELECT death_locationid as locationid,COUNT(*) AS c FROM death WHERE death_locationid IS NOT NULL 
                  AND (
                      id IN (SELECT death_id FROM person WHERE death_id IS NOT NULL AND id IN (?))
                      OR id IN (SELECT death_id FROM relative WHERE death_id IS NOT NULL AND id IN (?))
                      OR id IN (SELECT death_id FROM partner WHERE death_id IS NOT NULL AND id IN (?))
                  )
                  GROUP BY death_locationid
                  UNION ALL
                  SELECT locationid,COUNT(*) AS c FROM education WHERE locationid IS NOT NULL AND person_id IN (?) GROUP BY locationid
                  UNION ALL
                  SELECT graduation_locationid as locationid,COUNT(*) AS c FROM education WHERE graduation_locationid IS NOT NULL AND person_id IN (?) GROUP BY graduation_locationid
                  UNION ALL
                  SELECT locationid,COUNT(*) AS c FROM honour WHERE locationid IS NOT NULL AND person_id IN (?) GROUP BY locationid
                  UNION ALL
                  SELECT locationid,COUNT(*) AS c FROM property WHERE locationid IS NOT NULL AND person_id IN (?) GROUP BY locationid
                  UNION ALL
                  SELECT locationid,COUNT(*) AS c FROM rank WHERE locationid IS NOT NULL AND person_id IN (?) GROUP BY locationid
                  UNION ALL
                  SELECT residence_locationid as locationid,COUNT(*) AS c FROM residence WHERE residence_locationid IS NOT NULL AND person_id IN (?) GROUP BY residence_locationid
                  UNION ALL
                  SELECT locationid,COUNT(*) AS c FROM road_of_life WHERE locationid IS NOT NULL AND person_id IN (?) GROUP BY locationid
                  UNION ALL
                  SELECT locationid,COUNT(*) AS c FROM status_information WHERE locationid IS NOT NULL AND person_id IN (?) GROUP BY locationid
                  UNION ALL
                  SELECT wedding_locationid as locationid,COUNT(*) AS c FROM wedding WHERE wedding_locationid IS NOT NULL 
                  AND (
                      husband_id IN (?)
                      OR wife_id IN (?)
                  )
                  GROUP BY wedding_locationid
                  UNION ALL
                  SELECT locationid,COUNT(*) AS c FROM works WHERE locationid IS NOT NULL AND person_id IN (?) GROUP BY locationid
                ) locations
                GROUP BY locationId
                ORDER BY SUM(locations.c) DESC) locationData
                ON location.id = locationData.locationid
                WHERE location.latitude IS NOT NULL AND location.longitude IS NOT NULL;";
        
        $finalDBManager = $this->getDBManager();
        
        $dataArray = [];
        $typeArray = [];
        
        //23 is the count of ? in the query
        for($i = 0; $i < 23; $i++){
            $dataArray[] = $personIds;
            $typeArray[] = \Doctrine\DBAL\Connection::PARAM_INT_ARRAY;
        }

        $stmt = $finalDBManager->getConnection()->executeQuery($sql, $dataArray, $typeArray);
        
        return $stmt->fetchAll();
    }
    
    private function getDBManager(){
        return  $this->get('doctrine')->getManager('final');
    }
}


