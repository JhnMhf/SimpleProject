<?php

namespace UR\AmburgerBundle\Util;

/**
 * Description of DataMigrater
 *
 * @author johanna
 */
class MigrateProcess {
     
    const MAX_RUN_DURATION_IN_SECONDS = 55;
    
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

    public function run(){
        return $this->internalRun($this->getPersonIds());
    }
    
    //@TODO: Rollback if one person fails?
    private function internalRun($personIds){
        $startTime = time();
        
        for($i = 0; $i < count($personIds); $i++){
            $this->migratePerson($personIds[$i]);
            
            if((time() -$startTime) > MigrateProcess::MAX_RUN_DURATION_IN_SECONDS){
                $this->getLogger()->info("Stopping the run after ".$personIds[$i]. " since it already took longer than ".MigrateProcess::MAX_RUN_DURATION_IN_SECONDS);
                break;
            }
        }
        
        return count($personIds);
    }
    
    private function migratePerson($id){
        try{
            $this->getLogger()->info("Migration person with (old DB) id: ".$id);
            $person = $this->getMigrationUtils()->migratePerson($id);

            if(is_null($person)){
                $this->getLogger()->error("Unknown id");
            } else {
                $this->getLogger()->info("Migrated Person: ".$person);
            }
        } catch (\Exception $ex) {
            $this->getLogger()->error("An Exception occurred: ". $ex);
        }

    }
    
    private function getPersonIds(){
        $newDBManager = $this->get('doctrine')->getManager('new');
        
        $sql = 'SELECT ID,OID FROM OldAmburgerDB.ids WHERE OID NOT IN '
                . '(SELECT OID FROM NewAmburgerDB.person ORDER BY OID ASC) '
                . 'ORDER BY oid ASC LIMIT 100';
        
        
        $stmt = $newDBManager->getConnection()->prepare($sql);
        $stmt->execute();
        
        $ids = $stmt->fetchAll();
        
        $personIds = array();
        
        for($i = 0; $i < count($ids); $i++){
            $personIds[] = $ids[$i]['ID'];
        }
        
        return $personIds;
    }
    
    public function runWithTestData(){
        return $this->internalRun($this->getTestPersonIds());
    }

    private function getTestPersonIds() {
        return [4,
            9,
            39,
            41,
            48,
            88,
            204,
            210,
            331,
            734,
            831,
            897,
            1700,
            2692,
            2828,
            3019,
            3037,
            3345,
            5691,
            5751,
            12128,
            13840,
            14598,
            24484,
            35931,
            39153,
            44572,
            52739,
            69390,
            69955,
            70663,
            79476,
            81700,
            93996,
            94025,
            94098,
            94543,
            95279];
    }

}
