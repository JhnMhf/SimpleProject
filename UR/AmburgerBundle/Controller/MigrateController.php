<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MigrateController extends Controller
{

    private $LOGGER;
    private $migrationService;
    private $migrationUtil;


    private function getLogger()
    {
        if(is_null($this->LOGGER)){
            $this->LOGGER = $this->get('monolog.logger.migrateOld');
        }
        
        return $this->LOGGER;
    }
    
    private function getMigrationService()
    {
        if(is_null($this->migrationService)){
            $this->migrationService = $this->get("migrate_data.service");
        }
        
        return $this->migrationService;
    }
    
    private function getMigrationUtils()
    {
        if(is_null($this->migrationUtil)){
            $this->migrationUtil = $this->get("migration_util.service");
        }
        
        return $this->migrationUtil;
    }

    public function personAction($ID)
    {
        $this->getLogger()->info("Migrate Request for Person with ID ". $ID);

        $person = $this->getMigrationUtils()->migratePerson($ID);
        
        if(is_null($person)){
            return new Response("Invalid ID");
        }
        
        $this->getMigrationService()->clearProxyCache();
        
        return $this->forward('NewBundle:Default:json', array(
            'type' => 'id',
            'ID'  => $person->getId()
        ));
    }


}
