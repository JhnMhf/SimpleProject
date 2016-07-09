<?php

namespace UR\AmburgerBundle\Util;

/**
 * Description of DataMigrater
 *
 * @author johanna
 */
class MigrateProcess {
     
    private $LOGGER;
    private $container;
    
    private $migrationUtil;

    public function __construct($container)
    {
        $this->container = $container;
    }
    
    private function get($identifier) {
        return $this->container->get($identifier);
    }
    
        private function getLogger()
    {
        if(is_null($this->LOGGER)){
            $this->LOGGER = $this->get('monolog.logger.migratter');
        }
        
        return $this->LOGGER;
    }
   
    
    private function getMigrationUtils()
    {
        if(is_null($this->migrationUtil)){
            $this->migrationUtil = $this->get("migration_util.service");
        }
        
        return $this->migrationUtil;
    }

    //@TODO: Call over browser allows only a runtime of one minute. after that an error occurs.
    //@TODO: Rollback if one person fails?
    public function run(){
        $personIds = $this->getPersonIds();
        
        for($i = 0; $i < count($personIds); $i++){
            $this->migratePerson($personIds[$i]);
        }
        
        return count($personIds);
    }

    private function getPersonIds() {
        return [4,
            9,
            39,
            41,
            48,
            88,
            204,
            210,
            331,
            831,
            897,
            1700,
            2828,
            3037,
            3345,
            5691,
            12128,
            13840,
            44572,
            52739,
            69390,
            69955,
            70663,
            79476,
            81700,
            93996,
            95279];
    }

    private function migratePerson($id){
        try{
            $this->getLogger()->info("Migration person with old id: ".$id);
            $person = $this->getMigrationUtils()->migratePerson($id);

            if(is_null($person)){
                $this->getLogger()->warn("Unknown id");
            } else {
                $this->getLogger()->info("Migrated Person: ".$person);
            }
        } catch (Exception $ex) {
            $this->getLogger()->error("An Exception occured: ". $ex);
        }

    }
}
