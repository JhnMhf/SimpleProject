<?php

namespace UR\DB\NewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function jsonAction($type, $ID)
    {
        return $this->get("response_builder.service")->getJSONResponse($this->loadPersonByID($type, $ID));
    }
    
    public function xmlAction($type, $ID){
        return $this->get("response_builder.service")->getXMLResponse($this->loadPersonByID($type, $ID));
    }
    
    private function loadPersonByID($type, $ID){
        $newDBManager = $this->get('doctrine')->getManager('new');
        
        if($type == 'id'){
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

            return $person;
        } else if($type == 'oid'){
            $person = $newDBManager->getRepository('NewBundle:Person')->findOneByOid($ID);
        
            if(is_null($person)){
               //throw exception
            }

            return $person;
        }
            
        return null;
    }
    
    //http://stackoverflow.com/questions/5452760/truncate-foreign-key-constrained-table
    public function clearDatabaseAction(){
        $newDBManager = $this->get('doctrine')->getManager('new');
        
        $sql= "
                SET FOREIGN_KEY_CHECKS = 0;             
                TRUNCATE `baptism`;
                TRUNCATE `birth`;
                TRUNCATE `country`;
                TRUNCATE `date`;
                TRUNCATE `death`;
                TRUNCATE `education`;
                TRUNCATE `honour`;
                TRUNCATE `is_grandparent`;
                TRUNCATE `is_in_relationship_with`;
                TRUNCATE `is_parent`;
                TRUNCATE `is_parent_in_law`;
                TRUNCATE `is_sibling`;
                TRUNCATE `job`;
                TRUNCATE `job_class`;
                TRUNCATE `location`;
                TRUNCATE `nation`;
                TRUNCATE `partner`;
                TRUNCATE `person`;
                TRUNCATE `property`;
                TRUNCATE `rank`;
                TRUNCATE `relative`;
                TRUNCATE `religion`;
                TRUNCATE `residence`;
                TRUNCATE `road_of_life`;
                TRUNCATE `source`;
                TRUNCATE `status`;
                TRUNCATE `territory`;
                TRUNCATE `unique_id_sequence`;
                TRUNCATE `wedding`;
                TRUNCATE `works`;
                SET FOREIGN_KEY_CHECKS = 1;
                ";
        
        $stmt = $newDBManager->getConnection()->prepare($sql);
            
        $stmt->execute();
        
        return new Response('Truncated the whole new database!');
    }

    public function comparePersonAction($firstPersonOID, $secondPersonOID){
        $comparerService = $this->get('comparer.service');
        $newDBManager = $this->get('doctrine')->getManager('new');
        
        $personOne = $newDBManager->getRepository('NewBundle:Person')->findOneByOid($firstPersonOID);
        $personTwo = $newDBManager->getRepository('NewBundle:Person')->findOneByOid($secondPersonOID);
        
        $compareResult = $comparerService->comparePersons($personOne, $personTwo) ? "is the same" : "is not the same";
        
        return new Response('Given person '.$personOne.' '.$compareResult.' as person '.$personTwo);
    }
}
