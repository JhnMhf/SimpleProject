<?php
namespace UR\AmburgerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UR\AmburgerBundle\Entity\CronTask;


class TaskSetupCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
            ->setName('crontasks:setup')
            ->setDescription('Setups the cron tasks in the db');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('monolog.logger.cron')->info("Running the setup cron tasks command.");
        $output->writeln('<comment>Setting up Cron Tasks...</comment>');

        $this->output = $output;
        $em = $this->getContainer()->get('doctrine')->getManager('system');
        

        $this->insertCorrectionSessionInvalidationCronTask($em);
        $this->insertMigratterTask($em);
        
        // Flush database changes
        $em->flush();

        $output->writeln('<comment>Done!</comment>');
    }

    private function insertCorrectionSessionInvalidationCronTask($em){
        $existingTask = $em->getRepository('AmburgerBundle:CronTask')->findOneByName('CorrectionSessionInvalidator');
        
        if(is_null($existingTask)){
            $this->output->writeln('<comment>Adding Task CorrectionSessionInvalidator!</comment>');
            $newTask = new CronTask();

            $newTask
                ->setName('CorrectionSessionInvalidator')
                ->setRunInterval(3600) // Run once every hour
                ->setCommands(array('correctionsession:invalidate'));

            $em->persist($newTask);
        }
        
    }
    
    private function insertMigratterTask($em){
        $existingTask = $em->getRepository('AmburgerBundle:CronTask')->findOneByName('MigratterTask');
        
        if(is_null($existingTask)){
            $this->output->writeln('<comment>Adding Task MigratterTask!</comment>');
            $newTask = new CronTask();

            $newTask
                ->setName('MigratterTask')
                ->setRunInterval(90) // Run every 90 seconds (realistically only all 2 minutes)
                ->setCommands(array('personmigration:run'));

            $em->persist($newTask);
        }
        
    }
    
    
}

