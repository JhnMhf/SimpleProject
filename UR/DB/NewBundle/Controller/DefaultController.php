<?php

namespace UR\DB\NewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

    public function jsonAction($type, $ID) {
        return $this->get("response_builder.service")->getJSONResponse($this->loadPersonByID($type, $ID));
    }

    public function xmlAction($type, $ID) {
        return $this->get("response_builder.service")->getXMLResponse($this->loadPersonByID($type, $ID));
    }

    private function loadPersonByID($type, $ID) {
        $newDBManager = $this->get('doctrine')->getManager('new');

        if ($type == 'id') {
            $person = $newDBManager->getRepository('NewBundle:Person')->findOneById($ID);

            if (is_null($person)) {
                $person = $newDBManager->getRepository('NewBundle:Relative')->findOneById($ID);
            }

            if (is_null($person)) {
                $person = $newDBManager->getRepository('NewBundle:Partner')->findOneById($ID);
            }

            if (is_null($person)) {
                //throw exception
            }

            return $person;
        } else if ($type == 'oid') {
            $person = $newDBManager->getRepository('NewBundle:Person')->findOneByOid($ID);

            if (is_null($person)) {
                //throw exception
            }

            return $person;
        }

        return null;
    }

    //http://stackoverflow.com/questions/5452760/truncate-foreign-key-constrained-table
    public function clearDatabaseAction() {

        $sqlStatements = [
            //"SET FOREIGN_KEY_CHECKS = 0;",
            "TRUNCATE baptism CASCADE;",
            "TRUNCATE birth CASCADE;",
            "TRUNCATE country CASCADE;",
            "TRUNCATE date CASCADE;",
            "TRUNCATE death CASCADE;",
            "TRUNCATE education CASCADE;",
            "TRUNCATE honour CASCADE;",
            "TRUNCATE is_grandparent CASCADE;",
            "TRUNCATE is_in_relationship_with CASCADE;",
            "TRUNCATE is_parent CASCADE;",
            "TRUNCATE is_parent_in_law CASCADE;",
            "TRUNCATE is_sibling CASCADE;",
            "TRUNCATE job CASCADE;",
            "TRUNCATE job_class CASCADE;",
            "TRUNCATE location CASCADE;",
            "TRUNCATE nation CASCADE;",
            "TRUNCATE partner CASCADE;",
            "TRUNCATE person CASCADE;",
            "TRUNCATE property CASCADE;",
            "TRUNCATE rank CASCADE;",
            "TRUNCATE relative CASCADE;",
            "TRUNCATE religion CASCADE;",
            "TRUNCATE residence CASCADE;",
            "TRUNCATE road_of_life CASCADE;",
            "TRUNCATE source CASCADE;",
            "TRUNCATE status CASCADE;",
            "TRUNCATE territory CASCADE;",
            "TRUNCATE unique_id_sequence CASCADE;",
            "TRUNCATE wedding CASCADE;",
            "TRUNCATE works CASCADE;"
            //"SET FOREIGN_KEY_CHECKS = 1;"
        ];

        $newDBManager = $this->get('doctrine')->getManager('new');


        for ($i = 0; $i < count($sqlStatements); $i++) {
            $stmt = $newDBManager->getConnection()->prepare($sqlStatements[$i]);

            $stmt->execute();
        }


        return new Response('Truncated the whole new database!');
    }

    public function comparePersonAction($firstPersonOID, $secondPersonOID) {
        $comparerService = $this->get('comparer.service');
        $newDBManager = $this->get('doctrine')->getManager('new');

        $personOne = $newDBManager->getRepository('NewBundle:Person')->findOneByOid($firstPersonOID);
        $personTwo = $newDBManager->getRepository('NewBundle:Person')->findOneByOid($secondPersonOID);

        $compareResult = $comparerService->comparePersons($personOne, $personTwo) ? "is the same" : "is not the same";

        return new Response('Given person ' . $personOne . ' ' . $compareResult . ' as person ' . $personTwo);
    }

}
