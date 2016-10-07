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
            $this->LOGGER = $this->get('monolog.logger.default');
        }

        return $this->LOGGER;
    }

    private function getOldDBManager() {
        if (is_null($this->dbManager)) {
            $this->dbManager = $this->get('doctrine')->getManager('old');
        }

        return $this->dbManager;
    }
    
    public function loadPersonByOID($OID) {
        $IDData = $this->getOldDBManager()->getRepository('OldBundle:Ids')->findOneByOid($OID);

        $ID = $IDData->getId();

        return $this->loadInternalPersonById($ID, $OID);
    }
    
    private function loadInternalPersonById($ID, $OID) {
        $data = array();
        $data['oid'] = $OID;
        $data['person'] = $this->getOldDBManager()->getRepository('OldBundle:Person')->findOneById($ID);
        $data['herkunft'] = $this->getOldDBManager()->getRepository('OldBundle:Herkunft')->findOneById($ID);
        $data['tod'] = $this->getOldDBManager()->getRepository('OldBundle:Tod')->findOneById($ID);
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

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getHonourWithNativeQuery($oldPersonID) {
        $sql = "SELECT ID, `order`, ort, territorium, land, ehren, `von-ab`, bis, belegt, kommentar FROM `ehren` WHERE ID=:personID";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getPropertyWithNativeQuery($oldPersonID) {
        $sql = "SELECT ID, `order`, land, ort, territorium, besitz, `von-ab`, bis, belegt, kommentar FROM `besitz` WHERE ID=:personID";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getRankWithNativeQuery($oldPersonID) {
        $sql = "SELECT ID, `order`, ort, territorium, land, `von-ab`, bis, rang, rangklasse, belegt, kommentar FROM `rang` WHERE ID=:personID";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }
     
    private function getReligionDataWithNativeQuery($oldPersonID) {
        $sql = "SELECT ID, `order`, `von-ab`, konfession, konversion, belegt, kommentar FROM `religion` WHERE ID=:personID";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getRoadOfLifeWithNativeQuery($oldPersonID) {
        $sql = "SELECT ID, `order`, ort, territorium, land, stammterritorium, stammland, `von-ab`, bis, beruf, belegt, kommentar FROM `lebensweg` WHERE ID=:personID";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }
    
    private function getSourcesWithNativeQuery($oldPersonID) {
        $sql = "SELECT ID, `order`, bezeichnung, fundstelle, bemerkung, kommentar FROM `quelle` WHERE ID=:personID";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getStatusWithNativeQuery($oldPersonID) {
        $sql = "SELECT ID, `order`, ort, territorium, land, `von-ab`, bis, stand, belegt, kommentar FROM `stand`  WHERE ID=:personID";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getWorksWithNativeQuery($oldPersonID) {
        $sql = "SELECT `ID`, `order`, `land`, `werke`, `ort`, `von-ab`, `bis`, `belegt`, `kommentar`, `territorium` FROM `werke` WHERE ID=:personID";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    
    public function loadRelativeOrPartner($originOfData){
        $oldMainPersonID = $originOfData['idOfMainPerson'];
        $table = $originOfData['table'];
        $order = $originOfData['order'];
        $order2 = array_key_exists('order2',$originOfData) ? $originOfData['order2'] : null;
        $order3 = array_key_exists('order3',$originOfData) ? $originOfData['order3'] : null;
        $order4 = array_key_exists('order4',$originOfData) ? $originOfData['order4'] : null;
        
        $this->getLogger()->info("Loading Relative/Partner from OldDB using OldID: ".$oldMainPersonID. " in table ".$table);
        
        switch($table){
            case 'großmutter_muetterlicherseits':
                return $this->loadDataForNonPaternalGrandmother($oldMainPersonID, $order, $order2);
            case 'großvater_muetterlicherseits':
                return $this->loadDataForNonPaternalGrandfather($oldMainPersonID, $order, $order2);
            case 'großmutter_vaeterlicherseits':
                return $this->loadDataForPaternalGrandmother($oldMainPersonID, $order, $order2);
            case 'großvater_vaeterlicherseits':
                return $this->loadDataForPaternalGrandfather($oldMainPersonID, $order, $order2);
            case 'mutter':
                return $this->loadDataForMother($oldMainPersonID, $order);
            case 'vater':
                return $this->loadDataForFather($oldMainPersonID, $order);
            case 'geschwister':
                return $this->loadDataForSibling($oldMainPersonID, $order);
            case 'ehepartner':
                return $this->loadDataForMarriagePartner($oldMainPersonID, $order);
            case 'kind':
                return $this->loadDataForKind($oldMainPersonID, $order, $order2);
            case 'schwiegervater':
                
                break;
            case 'schwiegermutter':
                
                break;
            
            case 'anderer_partner':
                
                break;
            case 'partnerin_des_vaters':
                
                break;
            case 'partner_der_mutter':
                
                break;
            case 'ehepartner_des_geschwisters':
                
                break;
            case 'geschwisterkind':
                
                break;
            case 'ehepartner_des_kindes':
                
                break;
            case 'anderer_partner_des_kindes':
                
                break;
            case 'schwiegervater_des_kindes':
                
                break;
            case 'schwiegermutter_des_kindes':
                
                break;
            case 'mutter_des_geschwisters':
                
                break;
            case 'enkelkind':
                
                break;
        }
        
    }
    
    private function loadDataForNonPaternalGrandmother($oldMainPersonID, $order, $order2){
        $sql = "SELECT vornamen, name, nation "
                . "FROM `großmutter_muetterlicherseits` WHERE ID=:personID AND `order`= :order AND `order2` = :order2";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldMainPersonID);
        $stmt->bindValue('order', $order);
        $stmt->bindValue('order2', $order2);
        $stmt->execute();


        $dbData = $stmt->fetchAll();
        
        if(count($dbData) == 0){
            throw new \Exception("Could not load data from old DB for großmutter_muetterlicherseits");
        }
        
        $entry = $dbData[0];
        $data = array();
        $data['person'] = $this->createPersonArray('weiblich', $entry['name'], $entry['vornamen'],null,null,null, $entry['nation']);
        $data['herkunft'] = array();
        $data['tod'] = array();
        $data['ausbildung'] = array();
        $data['ehre'] = array();
        $data['eigentum'] = array();
        $data['rang'] = array();
        $data['religion'] = array();
        $data['lebensweg'] = array();
        $data['quellen'] = array();
        $data['status'] = array();
        $data['werke'] = array();
        $data['wohnung'] = array();
        
        return $data;
    }
    
    private function loadDataForNonPaternalGrandfather($oldMainPersonID, $order, $order2){
        $sql = "SELECT vornamen, name, gestorben, wohnort, nation, beruf, kommentar "
                . "FROM `großvater_muetterlicherseits` WHERE ID=:personID AND `order`= :order AND `order2` = :order2";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldMainPersonID);
        $stmt->bindValue('order', $order);
        $stmt->bindValue('order2', $order2);
        $stmt->execute();


        $dbData = $stmt->fetchAll();
        
        if(count($dbData) == 0){
            throw new \Exception("Could not load data from old DB for großvater_muetterlicherseits");
        }
        
        $entry = $dbData[0];
        $data = array();
        $data['person'] = $this->createPersonArray('männlich', $entry['name'], $entry['vornamen'], null,null,null, $entry['nation'], $entry['beruf'],null,null, $entry['kommentar']);
        $data['herkunft'] = array();
        $data['tod'] = $this->createTodArray(null,null,$entry['gestorben']);
        $data['ausbildung'] = array();
        $data['ehre'] = array();
        $data['eigentum'] = array();
        $data['rang'] = array();
        $data['religion'] = array();
        $data['lebensweg'] = array();
        $data['quellen'] = array();
        $data['status'] = array();
        $data['werke'] = array();
        $data['wohnung'] = $this->asArray($this->createWohnungArray(null,null,$entry['wohnort']));
        
        return $data;
    }
    
    private function loadDataForPaternalGrandmother($oldMainPersonID, $order, $order2){
        $sql = "SELECT vornamen, name, beruf, geburtsland "
                . "FROM `großmutter_vaeterlicherseits` WHERE ID=:personID AND `order`= :order AND `order2` = :order2";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldMainPersonID);
        $stmt->bindValue('order', $order);
        $stmt->bindValue('order2', $order2);
        $stmt->execute();


        $dbData = $stmt->fetchAll();
        
        if(count($dbData) == 0){
            throw new \Exception("Could not load data from old DB for großmutter_vaeterlicherseits");
        }
        
        $entry = $dbData[0];
        $data = array();
        $data['person'] = $this->createPersonArray('weiblich', $entry['name'], $entry['vornamen'], null,null,null, null, $entry['beruf']);
        $data['herkunft'] = $this->createHerkunftArray(null,null,null,null,null,$entry['geburtsland']);
        $data['tod'] = array();
        $data['ausbildung'] = array();
        $data['ehre'] = array();
        $data['eigentum'] = array();
        $data['rang'] = array();
        $data['religion'] = array();
        $data['lebensweg'] = array();
        $data['quellen'] = array();
        $data['status'] = array();
        $data['werke'] = array();
        $data['wohnung'] = array();
        
        return $data;
    }
    
    private function loadDataForPaternalGrandfather($oldMainPersonID, $order, $order2){
        $sql = "SELECT vornamen, name, geboren, geburtsort, geburtsland, geburtsterritorium, "
                . "gestorben, wohnort, wohnterritorium, nation, beruf, rang, stand, kommentar "
                . "FROM `großvater_vaeterlicherseits` WHERE ID=:personID AND `order`= :order AND `order2` = :order2";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldMainPersonID);
        $stmt->bindValue('order', $order);
        $stmt->bindValue('order2', $order2);
        $stmt->execute();


        $dbData = $stmt->fetchAll();
        
        if(count($dbData) == 0){
            throw new \Exception("Could not load data from old DB for großvater_vaeterlicherseits");
        }
        
        $entry = $dbData[0];
        $data = array();
        $data['person'] = $this->createPersonArray('männlich', $entry['name'], $entry['vornamen'], null,null,null, $entry['nation'], $entry['beruf'],null,null, $entry['kommentar']);
        $data['herkunft'] = $this->createHerkunftArray(null,null,null,$entry['geburtsort'],$entry['geburtsterritorium'],$entry['geburtsland'],$entry['geboren']);
        $data['tod'] = $this->createTodArray(null,null,$entry['gestorben']);
        $data['ausbildung'] = array();
        $data['ehre'] = array();
        $data['eigentum'] = array();
        $data['rang'] = $this->asArray($this->createRangArray($entry['rang']));
        $data['religion'] = array();
        $data['lebensweg'] = array();
        $data['quellen'] = array();
        $data['status'] = $this->asArray($this->createStatusArray($entry['stand']));
        $data['werke'] = array();
        $data['wohnung'] = $this->asArray($this->createWohnungArray(null,$entry['wohnterritorium'],$entry['wohnort']));
        
        return $data;
    }
    
    private function loadDataForMother($oldMainPersonID, $order){
        $sql = "SELECT vornamen, russ_vornamen, name, rufnamen, geboren, geburtsort, getauft, "
                . "gestorben, todesort, todesterritorium, begraben, friedhof, herkunftsort, "
                . "herkunftsland, herkunftsterritorium, wohnort, nation, konfession, ehelich, "
                . "stand, rang, besitz, beruf, kommentar "
                . "FROM `mutter` WHERE ID=:personID AND `order`= :order";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldMainPersonID);
        $stmt->bindValue('order', $order);
        $stmt->execute();


        $dbData = $stmt->fetchAll();
        
        if(count($dbData) == 0){
            throw new \Exception("Could not load data from old DB for mutter");
        }
        
        $entry = $dbData[0];
        $data = array();
        $data['person'] = $this->createPersonArray('weiblich', $entry['name'], $entry['vornamen'], $entry['russ_vornamen'],$entry['rufnamen'],null, $entry['nation'], $entry['beruf'],null,$entry['ehelich'], $entry['kommentar']);
        $data['herkunft'] = $this->createHerkunftArray($entry['herkunftsort'],$entry['herkunftsterritorium'],$entry['herkunftsland'],$entry['geburtsort'],null,null,$entry['geboren'],null,$entry['getauft']);
        $data['tod'] = $this->createTodArray($entry['todesort'],$entry['todesterritorium'],null,$entry['gestorben'],null,$entry['friedhof'],null,$entry['begraben']);
        $data['ausbildung'] = array();
        $data['ehre'] = array();
        $data['eigentum'] = $this->asArray($this->createEigentumArray($entry['besitz']));
        $data['rang'] = $this->asArray($this->createRangArray($entry['rang']));
        $data['religion'] = $this->asArray($this->createReligionArray($entry['konfession']));
        $data['lebensweg'] = array();
        $data['quellen'] = array();
        $data['status'] = $this->asArray($this->createStatusArray($entry['stand']));
        $data['werke'] = array();
        $data['wohnung'] = $this->asArray($this->createWohnungArray(null,null,$entry['wohnort']));
        
        return $data;
    }
    
    private function loadDataForFather($oldMainPersonID, $order){
        $sql = "SELECT `vornamen`,`name`,`russ_vornamen`,`rufnamen`,
                    `geboren`,`geburtsterritorium`,`geburtsort`,`geburtsland`,`herkunftsort`,`herkunftsterritorium`,`herkunftsland`,
                    `gestorben`,`todesort`,`todesterritorium`,`begraben`,`begräbnisort`,`getauft`,`taufort`,`wohnort`,`wohnterritorium`,`wohnland`,
                    `nation`,`beruf`,`stand`,`bildungsabschluss`,`rang`,`besitz`,`ehren`,`ehelich`,`konfession`,`ausbildung`,`kommentar` "
                . " FROM `vater` WHERE ID=:personID AND `order`= :order";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldMainPersonID);
        $stmt->bindValue('order', $order);
        $stmt->execute();


        $dbData = $stmt->fetchAll();
        
        if(count($dbData) == 0){
            throw new \Exception("Could not load data from old DB for vater");
        }
        
        $entry = $dbData[0];
        $data = array();
        $data['person'] = $this->createPersonArray('männlich', $entry['name'], $entry['vornamen'], $entry['russ_vornamen'],$entry['rufnamen'],null, $entry['nation'], $entry['beruf'],null,$entry['ehelich'], $entry['kommentar']);
        $data['herkunft'] = $this->createHerkunftArray($entry['herkunftsort'],$entry['herkunftsterritorium'],$entry['herkunftsland'],$entry['geburtsort'],$entry['geburtsterritorium'],$entry['geburtsland'],$entry['geboren'],$entry['taufort'],$entry['getauft']);
        $data['tod'] = $this->createTodArray($entry['todesort'],$entry['todesterritorium'],null,$entry['gestorben'],null,$entry['friedhof'],$entry['begräbnisort'],$entry['begraben']);
        $data['ausbildung'] = $this->asArray($this->createAusbildungsArray($entry['ausbildung']));
        $data['ehre'] = $this->asArray($this->createEhreArray($entry['ehren']));
        $data['eigentum'] = $this->asArray($this->createEigentumArray($entry['besitz']));
        $data['rang'] = $this->asArray($this->createRangArray($entry['rang']));
        $data['religion'] = $this->asArray($this->createReligionArray($entry['konfession']));
        $data['lebensweg'] = array();
        $data['quellen'] = array();
        $data['status'] = $this->asArray($this->createStatusArray($entry['stand']));
        $data['werke'] = array();
        $data['wohnung'] = $this->asArray($this->createWohnungArray($entry['wohnland'],$entry['wohnterritorium'],$entry['wohnort']));
        
        return $data;
    }
    
    private function loadDataForSibling($oldMainPersonID, $order){
        $sql = "SELECT vornamen, russ_vornamen, name, rufnamen, geschlecht, kommentar "
                . " FROM `geschwister` WHERE ID=:personID AND `order`= :order";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldMainPersonID);
        $stmt->bindValue('order', $order);
        $stmt->execute();


        $dbData = $stmt->fetchAll();
        
        if(count($dbData) == 0){
            throw new \Exception("Could not load data from old DB for geschwister");
        }
        
        $entry = $dbData[0];
        
        return $this->createSibling($entry, $order, $oldMainPersonID, $this->getOldDBManager());
    }
    
     private function createSibling($entry,$order, $oldPersonID, $oldDBManager) {
        $data = array();
        $data['person'] = $this->createPersonArray($entry['geschlecht'], $entry['name'], $entry['vornamen'], $entry['russ_vornamen'],$entry['rufnamen'],null, null, null,null,null, $entry['kommentar']);
        
        //additional data
        $siblingEducation = $this->getSiblingsEducationWithNativeQuery($oldPersonID, $order, $oldDBManager);

        $siblingHonour = $this->getSiblingsHonourWithNativeQuery($oldPersonID, $order, $oldDBManager);

        $siblingOrigin = $this->getSiblingsOriginWithNativeQuery($oldPersonID, $order, $oldDBManager);

        $siblingRoadOfLife = $this->getSiblingsRoadOfLifeWithNativeQuery($oldPersonID, $order, $oldDBManager);

        $siblingRank = $this->getSiblingsRankWithNativeQuery($oldPersonID, $order, $oldDBManager);

        $siblingStatus = $this->getSiblingsStatusWithNativeQuery($oldPersonID, $order, $oldDBManager);

        $siblingDeath = $this->getSiblingsDeathWithNativeQuery($oldPersonID, $order, $oldDBManager);

        if (count($siblingOrigin) > 0) {
            //origin
            for ($i = 0; $i < count($siblingOrigin); $i++) {
                $entry = $siblingOrigin[$i];
                if ($entry['geboren'] != null || $entry['geburtsort'] != null || $entry['geburtsland'] != null || $entry['kommentar'] != null || $entry['getauft'] != null || $entry['taufort'] != null) {
                    $data['herkunft'] = $this->createHerkunftArray(null,null,null,$entry['geburtsort'], null,$entry['geburtsland'], $entry['geboren'],$entry["taufort"], $entry["getauft"],null, $entry['kommentar']);
                }
            }
        }

        
        if (count($siblingDeath) > 0) {
            //death
            for ($i = 0; $i < count($siblingDeath); $i++) {
                $entry = $siblingDeath[$i];
                //death
                if (!is_null($entry["begräbnisort"]) ||
                        !is_null($entry["gestorben"]) ||
                        !is_null($entry["todesort"]) ||
                        !is_null($entry["friedhof"]) ||
                        !is_null($entry["todesursache"]) ||
                        !is_null($entry["kommentar"])) {
                    $data['tod'] = $this->createTodArray($entry['todesort'],null,null,$entry['gestorben'],null,$entry['friedhof'],$entry['begräbnisort'],$entry['begraben'],$entry['kommentar']);
                }
            }
        }

        if (count($siblingEducation) > 0) {
            $data['ausbildung'] = array();
            //education
            for ($i = 0; $i < count($siblingEducation); $i++) {
                $education = $siblingEducation[$i];
                $data['ausbildung'][] = $this->createAusbildungsArray($education['ausbildung'], $education['ort'],null,$education['land'],$education['bildungsabschluss'],null,null,$education['von-ab'],$education['bis'], $education['belegt']);
            }
        }

        if (count($siblingHonour) > 0) {
            $data['ehre'] = array();
            //honour
            for ($i = 0; $i < count($siblingHonour); $i++) {
                $honour = $siblingHonour[$i];
                $data['ehre'][] = $this->createEhreArray($honour['ehren'],null,null,$honour['land']);
            }
        }

        if (count($siblingRoadOfLife) > 0) {
            $data['lebensweg'] = array();
            //roadOfLife
            for ($i = 0; $i < count($siblingRoadOfLife); $i++) {
                $step = $siblingRoadOfLife[$i];
                $data['lebensweg'][] = $this->createLebenswegArray($step['ort'],$step['territorium'], null, null, $step['stammland'], $step['beruf'], $step['von-ab'], $step['bis'], $step['belegt'], $step['kommentar']);
            }
        }


        if (count($siblingRank) > 0) {
            $data['rang'] = array();
            //rank
            for ($i = 0; $i < count($siblingRank); $i++) {
                $rank = $siblingRank[$i];
                $data['rang'][] = $this->createRangArray($rank['rang'],null,null,null,$rank['land'],null,null,null,$rank['kommentar']);
            }
        }


        if (count($siblingStatus) > 0) {
            $data['status'] = array();
            //status
            for ($i = 0; $i < count($siblingStatus); $i++) {
                $status = $siblingStatus[$i];
                $data['status'][] = $this->createStatusArray($status['stand'], null,null,$status['land'],$status['von-ab']);
            }
        }

        return $data;
    }
    
    private function getSiblingsEducationWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, land, ort, `von-ab`, bis, ausbildung, bildungsabschluss, belegt 
        FROM `ausbildung_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsHonourWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, land, ehren
                FROM `ehren_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsOriginWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, geboren, geburtsort, geburtsland, getauft, taufort, kommentar
                FROM `herkunft_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsRoadOfLifeWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, ort, territorium, stammland, beruf, `von-ab`, bis, belegt, kommentar
                FROM `lebensweg_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsRankWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, land, rang, kommentar
                FROM `rang_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsStatusWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, land, stand, `von-ab`
                FROM `stand_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getSiblingsDeathWithNativeQuery($oldPersonID, $siblingNr, $oldDBManager) {
        $sql = "SELECT `ID`,`order`,`order2`,`begräbnisort`,`gestorben`,`todesort`,`friedhof`,`kommentar`,`todesursache` 
                FROM `tod_des_geschwisters` WHERE ID=:personID AND `order`=:siblingNr";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('siblingNr', $siblingNr);
        $stmt->execute();


        return $stmt->fetchAll();
    }
    
    private function loadDataForMarriagePartner($oldMainPersonID, $order){
        $sql = "SELECT vornamen, russ_vornamen, name, rufnamen, nation, herkunftsort, herkunftsland, 
                herkunftsterritorium, geboren, geburtsort, geburtsland, geburtsterritorium, 
                getauft, taufort, gestorben, todesort, friedhof, begraben, 
                begräbnisort, todesterritorium, todesland, todesursache, konfession, 
                beruf, stand, rang, ehren, besitz, 
                bildungsabschluss, kommentar
                FROM `ehepartner` WHERE ID=:personID AND `order` = :order";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldMainPersonID);
        $stmt->bindValue('order', $order);
        $stmt->execute();

        $dbData = $stmt->fetchAll();
        
        if(count($dbData) == 0){
            throw new \Exception("Could not load data from old DB for ehepartner");
        }
        
        $entry = $dbData[0];
        $data = array();
        $data['person'] = $this->createPersonArray('unbekannt', $entry['name'], $entry['vornamen'], $entry['russ_vornamen'],$entry['rufnamen'],null, $entry['nation'], $entry['beruf'],null,null, $entry['kommentar']);
        $data['herkunft'] = $this->createHerkunftArray($entry['herkunftsort'],$entry['herkunftsterritorium'],$entry['herkunftsland'],$entry['geburtsort'],$entry['geburtsterritorium'],$entry['geburtsland'],$entry['geboren'],$entry['taufort'],$entry['getauft']);
        $data['tod'] = $this->createTodArray($entry['todesort'],$entry['todesterritorium'],$entry['todesursache'],$entry['gestorben'],null,$entry['friedhof'],$entry['begräbnisort'],$entry['begraben']);
        $data['ausbildung'] = $this->asArray($this->createAusbildungsArray(null,null,null,null,$entry['bildungsabschluss']));
        $data['ehre'] = $this->asArray($this->createEhreArray($entry['ehren']));
        $data['eigentum'] = $this->asArray($this->createEigentumArray($entry['besitz']));
        $data['rang'] = $this->asArray($this->createRangArray($entry['rang']));
        $data['religion'] = $this->asArray($this->createReligionArray($entry['konfession']));
        $data['lebensweg'] = array();
        $data['quellen'] = array();
        $data['status'] = $this->asArray($this->createStatusArray($entry['stand']));
        $data['werke'] = array();
        $data['wohnung'] = array();
        
        return $data;
    }
    
    private function loadDataForKind($oldMainPersonID, $order, $order2){
        $sql = "SELECT vornamen, russ_vornamen, name, rufnamen, geboren, geburtsort, geschlecht, kommentar
                FROM `kind` WHERE ID=:personID AND `order` = :order AND `order2`=:order2";

        $stmt = $this->getOldDBManager()->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldMainPersonID);
        $stmt->bindValue('order', $order);
        $stmt->bindValue('order2', $order2);
        $stmt->execute();

        $dbData = $stmt->fetchAll();
        
        if(count($dbData) == 0){
            throw new \Exception("Could not load data from old DB for ehepartner");
        }
        
        $entry = $dbData[0];
        
        return $this->createKind($entry, $oldMainPersonID, $order, $order2, $this->getOldDBManager());
    }
    
    private function createKind($entry, $oldPersonId, $order, $order2, $oldDBManager){
        $data = array();
        $data['person'] = $this->createPersonArray($entry['geschlecht'], $entry['name'], $entry['vornamen'], $entry['russ_vornamen'],$entry['rufnamen'],null, null, null,null,null, $entry['kommentar']);
        

        //additional data
        $childEducation = $this->getChildsEducationWithNativeQuery($oldPersonID, $order, $order2, $oldDBManager);

        $childProperty = $this->getChildsPropertyWithNativeQuery($oldPersonID, $order, $order2, $oldDBManager);

        $childHonour = $this->getChildsHonourWithNativeQuery($oldPersonID, $order, $order2, $oldDBManager);

        $childOrigin = $this->getChildsOriginWithNativeQuery($oldPersonID, $order, $order2, $oldDBManager);

        $childRoadOfLife = $this->getChildsRoadOfLifeWithNativeQuery($oldPersonID, $order, $order2, $oldDBManager);

        $childRank = $this->getChildsRankWithNativeQuery($oldPersonID, $order, $order2, $oldDBManager);

        $childReligion = $this->getChildsReligionWithNativeQuery($oldPersonID, $order, $order2, $oldDBManager);

        $childStatus = $this->getChildsStatusWithNativeQuery($oldPersonID, $order, $order2, $oldDBManager);

        $childDeath = $this->getChildsDeathWithNativeQuery($oldPersonID, $order, $order2, $oldDBManager);

        //birth
        //geboren, geburtsort, from oldChild
        if (!is_null($entry["geboren"]) || !is_null($entry["geburtsort"]) || count($childOrigin) > 0) {
           
            if (count($childOrigin) == 0) {
                $data['herkunft'] = $this->createHerkunftArray(null,null,null,$entry['geburtsort'],null,null,$entry['geboren']);
            } else {
                for ($i = 0; $i < count($childOrigin); $i++) {
                    $origin = $childOrigin[$i];

                    $geburtsOrt = $oldChild["geburtsort"];
                    $geboren = $oldChild["geboren"];

                    if (!is_null($origin['geburtsort']) && $origin['geburtsort'] != $geburtsOrt) {
                        if (!is_null($geburtsOrt)) {
                            // add it with oder
                            $geburtsOrt .= " ODER " . $origin['geburtsort'];
                        } else {
                            $geburtsOrt = $origin['geburtsort'];
                        }
                    }

                    if (!is_null($origin['geboren']) && $origin['geboren'] != $geboren) {
                        if (!is_null($geboren)) {
                            //create date array? add comment?
                            $geboren .= ";" . $origin['geboren'];
                        } else {
                            $geboren = $origin['geboren'];
                        }
                    }
                    
                    $data['herkunft'] = $this->createHerkunftArray(null,null,null,$entry['geburtsort'],$entry['geburtsterritorium'],$entry['geburtsland'],$entry['geboren'],$origin['taufort'],$origin['getauft'],$origin['belegt'],$origin['kommentar']);
                }
            }
        }

        if (count($childDeath) > 0) {
            //death
            for ($i = 0; $i < count($childDeath); $i++) {
                $death = $childDeath[$i];
                //death
                $data['tod'] = $this->createTodArray($death['todesort'], $death['todesterritorium'], $death['todesland'], $death["todesursache"], $death["gestorben"], $death["friedhof"], $death["begräbnisort"], $death["begraben"], $death["kommentar"]);
            }
        }
        
        if (count($childEducation) > 0) {
            $data['ausbildung'] = array();
            //education
            for ($i = 0; $i < count($childEducation); $i++) {
                $education = $childEducation[$i];
                $data['ausbildung'][] = $this->createAusbildungsArray($education['ausbildung'], $education['ort'],null,$education['land'],$education['bildungsabschluss'], $education["bildungsabschlussdatum"], $education["bildungsabschlussort"],$education['von-ab'],$education['bis'], $education['belegt'], $education["kommentar"]);
            }
        }


        if (count($childProperty) > 0) {
            $data['eigentum'] = array();
            //property
            for ($i = 0; $i < count($childProperty); $i++) {
                $property = $childProperty[$i];
                $data['eigentum'][] = $this->createEigentumArray($property['besitz'], $property['ort'],$property['territorium'],$property['land'],$property['von-ab'],null,$property['belegt']);
            }
        }


        if (count($childHonour) > 0) {
            $data['ehren'] = $array();
            //honour
            for ($i = 0; $i < count($childHonour); $i++) {
                $honour = $childHonour[$i];
                $data['ehren'][] = $this->createEhreArray($honour['ehren'],$honour["ort"],null,$honour["land"], $honour["von-ab"]);
            }
        }

        if (count($childRoadOfLife) > 0) {
            $data['lebensweg'] = array();
            //roadOfLife
            for ($i = 0; $i < count($childRoadOfLife); $i++) {
                $step = $childRoadOfLife[$i];
                $data['lebensweg'][] = $this->createLebenswegArray($step['ort'],$step['territorium'], $step['land'], null, $step['stammland'], $step['beruf'], $step['von-ab'], $step['bis'], $step['belegt'], $step['kommentar']);
            }
        }


        if (count($childRank) > 0) {
            $data['rang'] = array();
            //rank
            for ($i = 0; $i < count($childRank); $i++) {
                $rank = $childRank[$i];
                $data['rang'][] = $this->createRangArray($rank['rang'],$rank["rangklasse"],$rank["ort"],null,$rank['land'],$rank["von-ab"], $rank["bis"], $rank["belegt"],$rank['kommentar']);
            }
        }

        //religion
        if (count($childReligion) > 0) {
            $data['religion'] = array();
            //religion
            for ($i = 0; $i < count($childReligion); $i++) {
                $religion = $childReligion[$i];
                $data['religion'][] = $this->createReligionArray($religion["konfession"],null,null,null,$religion["kommentar"]);
            }
        }


        if (count($childStatus) > 0) {
            $data['status'] = array();
            //status
            for ($i = 0; $i < count($childStatus); $i++) {
                $status = $childStatus[$i];
                $data['status'][] = $this->createStatusArray($status['stand'], $status["ort"],$status["territorium"],$status['land'],$status['von-ab'],null, $status["belegt"], $status["kommentar"]);
            }
        }

        
        return $data;
    }

    private function getChildsEducationWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, land, ort, ausbildung, `von-ab`, bis, bildungsabschluss, bildungsabschlussdatum, bildungsabschlussort, belegt, kommentar
            FROM `ausbildung_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsPropertyWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, land, ort, territorium, besitz, `von-ab`, belegt
                FROM `besitz_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function getChildsHonourWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, ort, land, `von-ab`, ehren
                    FROM `ehren_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsOriginWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, geboren, geburtsort, geburtsterritorium, geburtsland, getauft, taufort, belegt, kommentar
                FROM `herkunft_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsRoadOfLifeWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, ort, territorium, land, stammland, beruf, `von-ab`, bis, belegt, kommentar
                FROM `lebensweg_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsRankWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, ort, land, rang, rangklasse, `von-ab`, bis, belegt, kommentar
                FROM `rang_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsReligionWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, konfession, kommentar
                FROM `religion_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsStatusWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT ID, `order`, order2, order3, ort, territorium, land, stand, `von-ab`, belegt, kommentar
                FROM `stand_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }

    private function getChildsDeathWithNativeQuery($oldPersonID, $marriageOrder, $childOrder, $oldDBManager) {
        $sql = "SELECT `ID`,`order`,`order2`,`order3`,`todesort`,`todesterritorium`,`gestorben`,`begräbnisort`,`todesursache`,`friedhof`,`begraben`,`todesland`,`kommentar` 
                FROM `tod_des_kindes` WHERE ID=:personID AND `order`=:marriageOrder AND `order2`=:childOrder";

        $stmt = $oldDBManager->getConnection()->prepare($sql);
        $stmt->bindValue('personID', $oldPersonID);
        $stmt->bindValue('marriageOrder', $marriageOrder);
        $stmt->bindValue('childOrder', $childOrder);
        $stmt->execute();


        return $stmt->fetchAll();
    }
    
    private function asArray($element){
        $array = array();
        $array[] = $element;
        return $array;
    }
    
    //@TODO: Improve so that no empty element gets returned.
    private function createPersonArray($geschlecht, $name=null, $vornamen=null, $russ_vorname=null,$rufname=null,$geburtsname=null, $ursp_nation=null, $beruf=null, $berufsklasse=null,$ehelich=null, $kommentar=null){
        $person = [];
        $person['vornamen'] = $vornamen;
        $person['russ_vornamen'] = $russ_vorname;
        $person['name'] = $name;
        $person['rufname'] = $rufname;
        $person['geburtsname'] = $geburtsname;
        $person['geschlecht'] = $geschlecht;
        $person['beruf'] = $beruf;
        $person['ehelich'] = $ehelich;
        $person['berufsklasse'] = $berufsklasse;
        $person['ursp_nation'] = $ursp_nation;
        $person['kommentar'] = $kommentar;
        
        return $person;
    }
    
    private function createHerkunftArray($herkunftsort, $herkunftsterritorium=null, $herkunftsland=null, $geburtsort=null, $geburtsterritorium=null, $geburtsland=null, $geboren=null, $taufort=null,$getauft=null,$belegt=null,$kommentar=null){
        $herkunft = [];
        $herkunft['taufort'] = $taufort;
        $herkunft['getauft'] = $getauft;
        $herkunft['herkunftsort'] = $herkunftsort;
        $herkunft['herkunftsterritorium'] = $herkunftsterritorium;
        $herkunft['herkunftsland'] = $herkunftsland;
        $herkunft['geburtsort'] = $geburtsort;
        $herkunft['geburtsterritorium'] = $geburtsterritorium;
        $herkunft['geburtsland'] = $geburtsland;
        $herkunft['geboren'] = $geboren;
        $herkunft['belegt'] = $belegt;
        $herkunft['kommentar'] = $kommentar;
        
        return $herkunft;
    }
    private function createTodArray($todesort,$todesterritorium=null, $todesland = null, $gestorben=null, $todesursache=null,  $friedhof=null, $begräbnisort=null, $begraben = null, $kommentar=null){
        $tod = [];
        $tod['todesort'] = $todesort;
        $tod['gestorben'] = $gestorben;        
        $tod['todesursache'] = $todesursache;        
        $tod['todesterritorium'] = $todesterritorium;        
        $tod['friedhof'] = $friedhof;
        $tod['begräbnisort'] = $begräbnisort;
        $tod['begraben'] = $begraben;
        $tod['todesland'] = $todesland;
        $tod['kommentar'] = $kommentar;
        
        return $tod;
    }
    
    private function createAusbildungsArray($ausbildung,$ort=null, $territorium=null, $land=null,  $bildungsabschluss=null, $bildungsabschlussdatum=null, $bildungsabschlussort=null, $vonAb=null, $bis=null, $belegt=null, $kommentar=null){
        $education = [];
        
        $education['ausbildung'] = $ausbildung;
        $education['land'] = $land;
        $education['territorium'] = $territorium;
        $education['ort'] = $ort;
        $education['von-ab'] = $vonAb;
        $education['bis'] = $bis;
        $education['belegt'] = $belegt;
        $education['bildungsabschluss'] = $bildungsabschluss;
        $education['bildungsabschlussdatum'] = $bildungsabschlussdatum;
        $education['bildungsabschlussort'] = $bildungsabschlussort;
        $education['kommentar'] = $kommentar;
        
        return $education;
    }
    private function createEhreArray($ehren,$ort=null, $territorium=null, $land=null,  $vonAb=null, $bis=null, $belegt=null, $kommentar=null){
        $honor = [];
        
        $honor['ehren'] = $ehren;
        $honor['land'] = $land;
        $honor['territorium'] = $territorium;
        $honor['ort'] = $ort;
        $honor['von-ab'] = $vonAb;
        $honor['bis'] = $bis;
        $honor['belegt'] = $belegt;
        $honor['kommentar'] = $kommentar;
        
        return $honor;
    }
    private function createEigentumArray($besitz,$ort=null, $territorium=null, $land=null,  $vonAb=null, $bis=null, $belegt=null, $kommentar=null){
        $property = [];
        
        $property['besitz'] = $besitz;
        $property['land'] = $land;
        $property['territorium'] = $territorium;
        $property['ort'] = $ort;
        $property['von-ab'] = $vonAb;
        $property['bis'] = $bis;
        $property['belegt'] = $belegt;
        $property['kommentar'] = $kommentar;
        
        return $property;
    }
    private function createRangArray($rang, $rangklasse=null,$ort=null, $territorium=null, $land=null, $vonAb=null, $bis=null, $belegt=null, $kommentar=null){
        $rank = [];
        
        $rank['rang'] = $rang;
        $rank['rangklasse'] = $rangklasse;
        $rank['land'] = $land;
        $rank['territorium'] = $territorium;
        $rank['ort'] = $ort;
        $rank['von-ab'] = $vonAb;
        $rank['bis'] = $bis;
        $rank['belegt'] = $belegt;
        $rank['kommentar'] = $kommentar;
        
        return $rank;
    }
    private function createReligionArray($konfession, $konversion=null, $vonAb=null, $bis=null, $kommentar=null){
        $religion = [];
        
        $religion['konfession'] = $konfession;
        $religion['konversion'] = $konversion;
        $religion['von-ab'] = $vonAb;
        $religion['bis'] = $bis;
        $religion['kommentar'] = $kommentar;
        
        return $religion;
    }
    private function createLebenswegArray($ort, $territorium=null, $land=null, $stammterritorium=null, $stammland=null,$beruf=null, $vonAb=null, $bis=null, $belegt=null, $kommentar=null){
        $roadOfLife = [];
        
        $roadOfLife['stammland'] = $stammland;
        $roadOfLife['stammterritorium'] = $stammterritorium;
        $roadOfLife['beruf'] = $beruf;
        $roadOfLife['land'] = $land;
        $roadOfLife['territorium'] = $territorium;
        $roadOfLife['ort'] = $ort;
        $roadOfLife['von-ab'] = $vonAb;
        $roadOfLife['bis'] = $bis;
        $roadOfLife['belegt'] = $belegt;
        $roadOfLife['kommentar'] = $kommentar;
        
        return $roadOfLife;
    }
    private function createStatusArray($stand,$ort=null, $territorium=null, $land=null, $vonAb=null, $bis=null, $belegt=null, $kommentar=null){
        $status = [];
        
        $status['stand'] = $stand;
        $status['land'] = $land;
        $status['territorium'] = $territorium;
        $status['ort'] = $ort;
        $status['von-ab'] = $vonAb;
        $status['bis'] = $bis;
        $status['belegt'] = $belegt;
        $status['kommentar'] = $kommentar;
        
        return $status;
    }
    private function createWerkeArray($werke,$ort=null, $territorium=null, $land=null, $vonAb=null, $bis=null, $belegt=null, $kommentar=null){
        $works = [];
        
        $works['werke'] = $werke;
        $works['land'] = $land;
        $works['territorium'] = $territorium;
        $works['ort'] = $ort;
        $works['von-ab'] = $vonAb;
        $works['bis'] = $bis;
        $works['belegt'] = $belegt;
        $works['kommentar'] = $kommentar;
        
        return $works;
    }
    private function createWohnungArray($wohnland, $wohnterritorium=null, $wohnort=null){
        $wohnung = [];

        $wohnung['wohnland'] = $wohnland;
        $wohnung['wohnterritorium'] = $wohnterritorium;
        $wohnung['wohnort'] = $wohnort;
        
        return $wohnung;
    }
}
