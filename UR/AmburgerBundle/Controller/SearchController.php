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
class SearchController extends Controller{
    
    public function searchAction()
    {
        $request = $this->getRequest();
        
        if(count($request->query->all()) == 0){
            $response = new Response();
            $response->setContent("Missing GET Parameters");
            $response->setStatusCode("406");
            return $response;
        }
        
        //@TODO: Check if at least one valid parameter is given
        //http://symfony.com/doc/current/components/http_foundation.html
        //http://api.symfony.com/3.1/Symfony/Component/HttpFoundation/ParameterBag.html
        
        $searchQuery = $request->query->get('searchQuery');
        $onlyMainPersons = $request->query->get('onlyMainPersons');
        $lastname = $request->query->get('lastname');
        $firstname = $request->query->get('firstname');
        $patronym = $request->query->get('patronym');
        $location = $request->query->get('location');
        $territory = $request->query->get('territory');
        $country = $request->query->get('country');
        $date = $request->query->get('date');
        
        $fromDate = $request->query->get('fromDate');
        $toDate = $request->query->get('toDate');
        
        $listOfPossibleIds = $this->get('search.util')->search($searchQuery, 
                $onlyMainPersons, $lastname, $firstname, $patronym, $location,
                $territory, $country, $date, $fromDate, $toDate);
        
        $serializer = $this->get('serializer');
        $json = $serializer->serialize($listOfPossibleIds, 'json');
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($json);
        
        return $jsonResponse;
    }
    
    public function loadPersonListAction(){
        $content = $this->get("request")->getContent();
        
        if (!empty($content))
        {

            $serializer = $this->get('serializer');
            
            $personIds = $serializer->deserialize($content, 'array', 'json');
            
            $personData = $this->loadPersonData($personIds);

            $json = $serializer->serialize($personData, 'json');
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
    
    private function loadPersonData($ids){
        $personIds = $this->internalLoadData($ids);
        
        $dateRefLoader = $this->get('date_reference_loader');
        //@TODO: Replace with final em
        $em = $this->getDBManager();
        
        //enrich datedata
        for($i = 0; $i < count($personIds); $i++){
            //use reference
            $personData = &$personIds[$i];
            
            if(!empty($personData['birth_date'])){
                $personData['birth_date'] = $dateRefLoader->loadDateReferenceFromString($em,$personData['birth_date']);
            }
                        
            if(!empty($personData['baptism_date'])){
                $personData['baptism_date'] = $dateRefLoader->loadDateReferenceFromString($em,$personData['baptism_date']);
            }
                        
            if(!empty($personData['death_date'])){
                $personData['death_date'] = $dateRefLoader->loadDateReferenceFromString($em,$personData['death_date']);
            }
                        
            if(!empty($personData['funeral_date'])){
                $personData['funeral_date'] = $dateRefLoader->loadDateReferenceFromString($em,$personData['funeral_date']);
            }
        }
        
        usort($personIds,array($this, 'compareEntries'));
        
        return $personIds;
    }
    
    private function compareEntries($a, $b){
        return ($a['id'] < $b['id']) ? -1 : 1;
    }
    
    private function internalLoadData($ids){
        $persons = $this->loadPersons($ids);
        
        if(count($ids) == count($persons)){
            return $persons;
        }
        
        $relatives = $this->loadRelatives($ids);
        
        $personData = array_merge($persons, $relatives);
        
        if(count($ids) == count($personData)){
            return $personData;
        }
        
        $partners = $this->loadPartners($ids);
        
        $personData = array_merge($personData, $partners);
        
        return $personData;
    }
    
    //@TODO: Use final instead of new
    //http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/data-retrieval-and-manipulation.html#list-of-parameters-conversion
    private function loadPersons($ids){
        $finalDBManager = $this->getDBManager();

        $stmt =  $finalDBManager->getConnection()->executeQuery(
                "SELECT person.id as id, first_name, patronym, last_name, 'person' as type, birth.birth_dateid as birth_date, baptism.baptism_dateid as baptism_date, death.death_dateid as death_date, death.funeral_dateid as funeral_date "
                . "FROM person "
                . "LEFT JOIN birth "
                . "ON person.birth_id = birth.id "
                . "LEFT JOIN baptism "
                . "ON person.baptism_id = baptism.id "
                . "LEFT JOIN death "
                . "ON person.death_id = death.id "
                . "WHERE person.id IN (?)", array($ids), 
                array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY));

        return $stmt->fetchAll();
    }
    
    private function loadRelatives($ids){
        $finalDBManager = $this->getDBManager();
        
        
        $stmt =  $finalDBManager->getConnection()->executeQuery(
                "SELECT relative.id as id, first_name, patronym, last_name, 'relative' as type, birth.birth_dateid as birth_date, baptism.baptism_dateid as baptism_date, death.death_dateid as death_date, death.funeral_dateid as funeral_date "
                . "FROM relative "
                . "LEFT JOIN birth "
                . "ON relative.birth_id = birth.id "
                . "LEFT JOIN baptism "
                . "ON relative.baptism_id = baptism.id "
                . "LEFT JOIN death "
                . "ON relative.death_id = death.id "
                . "WHERE relative.id IN (?)", array($ids), 
                array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY));

        return $stmt->fetchAll();
    }
    
    private function loadPartners($ids){
        $finalDBManager = $this->getDBManager();
        
        $stmt =  $finalDBManager->getConnection()->executeQuery(
                "SELECT partner.id as id, first_name, patronym, last_name, 'partner' as type, birth.birth_dateid as birth_date, baptism.baptism_dateid as baptism_date, death.death_dateid as death_date, death.funeral_dateid as funeral_date "
                . "FROM partner "
                . "LEFT JOIN birth "
                . "ON partner.birth_id = birth.id "
                . "LEFT JOIN baptism "
                . "ON partner.baptism_id = baptism.id "
                . "LEFT JOIN death "
                . "ON partner.death_id = death.id "
                . "WHERE partner.id IN (?)", array($ids), 
                array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY));

        return $stmt->fetchAll();
    }
    
        
    private function getDBManager(){
        return $this->get('doctrine')->getManager('final');
    }
}
