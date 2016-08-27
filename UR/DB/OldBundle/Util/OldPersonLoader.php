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

    public function __construct($container) {
        $this->container = $container;
    }

    private function get($identifier) {
        return $this->container->get($identifier);
    }

    private function getLogger() {
        if (is_null($this->LOGGER)) {
            $this->LOGGER = $this->get('monolog.logger.migratter');
        }

        return $this->LOGGER;
    }

    private function getDBManager() {
        if (is_null($this->dbManager)) {
            $this->dbManager = $this->get('doctrine')->getManager('old');
        }

        return $this->dbManager;
    }

    public function loadPersonByOID($OID) {
        $IDData = $this->getDBManager()->getRepository('OldBundle:Ids')->findOneByOid($OID);

        $ID = $IDData->getId();

        return $this->loadInternalPersonById($ID, $OID);
    }

    public function loadPersonById($ID) {
        $IDData = $this->getDBManager()->getRepository('OldBundle:Ids')->findOneById($ID);

        $OID = $IDData->getOid();

        return $this->loadInternalPersonById($ID, $OID);
    }

    private function loadInternalPersonById($ID, $OID) {
        $data = array();
        $data['oid'] = $OID;
        $data['person'] = $this->getDBManager()->getRepository('OldBundle:Person')->findOneById($ID);
        $data['herkunft'] = $this->getDBManager()->getRepository('OldBundle:Herkunft')->findOneById($ID);
        $data['tod'] = $this->getDBManager()->getRepository('OldBundle:Tod')->findOneById($ID);
        $data['ausbildung'] = $this->getEducationWithNativeQuery($ID);
        $data['ehre'] = $this->getHonourWithNativeQuery($ID);
        $data['eigentum'] = $this->getPropertyWithNativeQuery($ID);
        $data['rang'] = $this->getRankWithNativeQuery($ID);
        $data['religion'] = $this->getReligionDataWithNativeQuery($ID);
        $data['lebensweg'] = $this->getRoadOfLifeWithNativeQuery($ID);
        $data['quellen'] = $this->getSourcesWithNativeQuery($ID);
        $data['status'] = $this->getStatusWithNativeQuery($ID);
        $data['werke'] = $this->getWorksWithNativeQuery($ID);

        return $data;
    }

    private function getEducationWithNativeQuery($oldPersonID) {
        $sql = "SELECT ID, `order`, ort, land, territorium, ausbildung, bildungsabschluss, bildungsabschlussdatum, bildungsabschlussort, `von-ab`, bis, belegt, kommentar FROM `ausbildung` WHERE ID=:personID";

        $stmt = $this->getDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getHonourWithNativeQuery($oldPersonID) {
        $sql = "SELECT ID, `order`, ort, territorium, land, ehren, `von-ab`, bis, belegt, kommentar FROM `ehren` WHERE ID=:personID";

        $stmt = $this->getDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getPropertyWithNativeQuery($oldPersonID) {
        $sql = "SELECT ID, `order`, land, ort, territorium, besitz, `von-ab`, bis, belegt, kommentar FROM `besitz` WHERE ID=:personID";

        $stmt = $this->getDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getRankWithNativeQuery($oldPersonID) {
        $sql = "SELECT ID, `order`, ort, territorium, land, `von-ab`, bis, rang, rangklasse, belegt, kommentar FROM `rang` WHERE ID=:personID";

        $stmt = $this->getDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }
     
    private function getReligionDataWithNativeQuery($oldPersonID) {
        $sql = "SELECT ID, `order`, `von-ab`, konfession, konversion, belegt, kommentar FROM `religion` WHERE ID=:personID";

        $stmt = $this->getDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getRoadOfLifeWithNativeQuery($oldPersonID) {
        $sql = "SELECT ID, `order`, ort, territorium, land, stammterritorium, stammland, `von-ab`, bis, beruf, belegt, kommentar FROM `lebensweg` WHERE ID=:personID";

        $stmt = $this->getDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }
    
    private function getSourcesWithNativeQuery($oldPersonID) {
        $sql = "SELECT ID, `order`, bezeichnung, fundstelle, bemerkung, kommentar FROM `quelle` WHERE ID=:personID";

        $stmt = $this->getDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getStatusWithNativeQuery($oldPersonID) {
        $sql = "SELECT ID, `order`, ort, territorium, land, `von-ab`, bis, stand, belegt, kommentar FROM `stand`  WHERE ID=:personID";

        $stmt = $this->getDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getWorksWithNativeQuery($oldPersonID) {
        $sql = "SELECT `ID`, `order`, `land`, `werke`, `ort`, `von-ab`, `bis`, `belegt`, `kommentar`, `territorium` FROM `werke` WHERE ID=:personID";

        $stmt = $this->getDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

}
