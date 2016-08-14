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
        $newDBManager = $this->get('doctrine')->getManager('final');
        
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
        
        return new Response('Truncated the whole final database!');
    }
}
