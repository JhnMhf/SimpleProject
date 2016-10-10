<?php

namespace UR\DB\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FinalBundle:Default:index.html.twig', array('name' => $name));
    }
    
    //http://stackoverflow.com/questions/5452760/truncate-foreign-key-constrained-table
    public function clearDatabaseAction(){
        $mysql = true;
        
        if($mysql){
            $sqlStatements = [
                "SET FOREIGN_KEY_CHECKS = 0;",
                "TRUNCATE baptism;",
                "TRUNCATE birth;",
                "TRUNCATE country;",
                "TRUNCATE date_information;",
                "TRUNCATE death;",
                "TRUNCATE education;",
                "TRUNCATE honour;",
                "TRUNCATE is_grandparent;",
                "TRUNCATE is_parent;",
                "TRUNCATE is_parent_in_law;",
                "TRUNCATE is_sibling;",
                "TRUNCATE job;",
                "TRUNCATE job_class;",
                "TRUNCATE location;",
                "TRUNCATE nation;",
                "TRUNCATE partner;",
                "TRUNCATE person;",
                "TRUNCATE property;",
                "TRUNCATE rank;",
                "TRUNCATE relative;",
                "TRUNCATE religion;",
                "TRUNCATE residence;",
                "TRUNCATE road_of_life;",
                "TRUNCATE source_information;",
                "TRUNCATE status_information;",
                "TRUNCATE territory;",
                "TRUNCATE unique_id_sequence;",
                "TRUNCATE wedding;",
                "TRUNCATE works;",
                "SET FOREIGN_KEY_CHECKS = 1;"
                ];
        } else {
            $sqlStatements = [
                //"SET FOREIGN_KEY_CHECKS = 0;",
                "TRUNCATE baptism CASCADE;",
                "TRUNCATE birth CASCADE;",
                "TRUNCATE country CASCADE;",
                "TRUNCATE date_information CASCADE;",
                "TRUNCATE death CASCADE;",
                "TRUNCATE education CASCADE;",
                "TRUNCATE honour CASCADE;",
                "TRUNCATE is_grandparent CASCADE;",
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
                "TRUNCATE source_information CASCADE;",
                "TRUNCATE status_information CASCADE;",
                "TRUNCATE territory CASCADE;",
                "TRUNCATE unique_id_sequence CASCADE;",
                "TRUNCATE wedding CASCADE;",
                "TRUNCATE works CASCADE;"
                //"SET FOREIGN_KEY_CHECKS = 1;"
            ];
        }

        $finalDBManager = $this->get('doctrine')->getManager('final');


        for ($i = 0; $i < count($sqlStatements); $i++) {
            $stmt = $finalDBManager->getConnection()->prepare($sqlStatements[$i]);

            $stmt->execute();
        }


        return new Response('Truncated the whole final database!');
    }
}
