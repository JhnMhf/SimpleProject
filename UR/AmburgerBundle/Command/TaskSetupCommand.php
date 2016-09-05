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
        

        $this->createOrUpdateCorrectionSessionInvalidationCronTask($em);
        $this->createOrUpdateMigratterTask($em);
        
        // Flush database changes
        $em->flush();

        $output->writeln('<comment>Done!</comment>');
    }

    private function createOrUpdateCorrectionSessionInvalidationCronTask($em){
        $name = 'CorrectionSessionInvalidator';
        $runInterval = 3600;
        $commands = array('correctionsession:invalidate');
        $active = true;

        $this->createOrUpdateTask($em, $name, $runInterval, $commands, $active);
    }
    
    private function createOrUpdateMigratterTask($em){
        $name = 'MigratterTask';
        $runInterval = 90;
        $commands = array('personmigration:run');
        $active = true;

        $this->createOrUpdateTask($em, $name, $runInterval, $commands, $active);
    }
    
    private function createOrUpdateTask($em, $name, $runInterval, $commands, $active){
        $existingTask = $em->getRepository('AmburgerBundle:CronTask')->findOneByName($name);
        
        if(is_null($existingTask)){
            $this->output->writeln(sprintf('<comment>Adding Task %s!</comment>',$name));
            
            $newTask = new CronTask();
            
            $newTask
                ->setName($name)
                ->setRunInterval($runInterval) // Run every 90 seconds (realistically only all 2 minutes)
                ->setCommands($commands)
                ->setActive($active);
        
            $em->persist($newTask);
        } else {
            $this->output->writeln(sprintf('<comment>Updating Task %s!</comment>', $name));
            $existingTask
                    ->setRunInterval($runInterval) // Run every 90 seconds (realistically only all 2 minutes)
                    ->setCommands($commands)
                    ->setActive($active);
            
            $em->merge($existingTask);
        }
    }
    
    
}

