<?php

namespace UR\DB\OldBundle\Util;

/**
 * Description of OldPersonLoader
 *
 * @author johanna
 */
class OldPersonLoader {
     
    private $LOGGER;
    private $container;
    
    private $dbManager;
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
   
    private function getDBManager()
    {
        if(is_null($this->dbManager)){
            $this->dbManager = $this->get('doctrine')->getManager('old');
        }
        
        return $this->dbManager;
    }
    
    public function loadPersonByOID($OID){
        $IDData = $this->getDBManager()->getRepository('OldBundle:Ids')->findOneByOid($OID);

        $ID = $IDData->getId();
        
        return $this->loadInternalPersonById($ID, $OID);
    }
    
    public function loadPersonById($ID){
        $IDData = $this->getDBManager()->getRepository('OldBundle:Ids')->findOneById($ID);

        $OID = $IDData->getOid();
        
        return $this->loadInternalPersonById($ID, $OID);
    }
    
    private function loadInternalPersonById($ID, $OID){
        $data = array();
        $data['oid'] = $OID;
        $data['person'] = $this->getDBManager()->getRepository('OldBundle:Person')->findOneById($ID);
        $data['herkunft'] = $this->getDBManager()->getRepository('OldBundle:Herkunft')->findOneById($ID);
        $data['tod'] = $this->getDBManager()->getRepository('OldBundle:Tod')->findOneById($ID);
        
        
        return $data;
    }

}
