<?php
namespace UR\AmburgerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\StringInput;
use UR\AmburgerBundle\Entity\TaskWorker;

//https://inuits.eu/blog/creating-automated-interval-based-cron-tasks-symfony2
//http://symfony.com/doc/current/components/console.html
//http://devvness.blogspot.de/2013/11/scheduled-tasks-in-symfony2-symfony.html


//running as crontask with this schedule: 
//*/5 * * * * php /home/johanna/Masterarbeit/Symfony/amburger/app/console crontasks:run

class CronTasksRunCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
            ->setName('crontasks:run')
            ->setDescription('Runs Cron Tasks if needed');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('monolog.logger.cron')->info("Running cron tasks...");
        $output->writeln('<comment>Running Cron Tasks...</comment>');

        $this->output = $output;
        $em = $this->getContainer()->get('doctrine')->getManager('system');
        
        $taskWorkers = $em->getRepository('AmburgerBundle:TaskWorker')->findAll();
        
        if(is_null($taskWorkers) || count($taskWorkers) == 0){
            $newTaskWorker = new TaskWorker();
            $newTaskWorker->setRunning(true);
            
            $em->persist($newTaskWorker);
        } else if(!is_null($taskWorkers) && count($taskWorkers) == 1 && !$taskWorkers[0]->getRunning()){
            $taskWorkers[0]->setRunning(true);
            
            $em->merge($taskWorkers[0]);
        } else {
            $output->writeln('<comment>Previous worker is still running. Skipping this run.</comment>');
            $this->getContainer()->get('monolog.logger.cron')->info("Previous worker is still running. Skipping this run.");
            return;
        }
        
        $em->flush();
        
        $this->runTasks($em);
        
        $taskWorkers = $em->getRepository('AmburgerBundle:TaskWorker')->findAll();
        $taskWorkers[0]->setRunning(false);
        $em->merge($taskWorkers[0]);
        $em->flush();

        $output->writeln('<comment>Done!</comment>');
    }
    
    private function runTasks($em){
        $crontasks = $em->getRepository('AmburgerBundle:CronTask')->findAll();

        foreach ($crontasks as $crontask) {
            // Get the last run time of this task, and calculate when it should run next
            $lastrun = $crontask->getLastRun() ? $crontask->getLastRun()->format('U') : 0;
            $nextrun = $lastrun + $crontask->getRunInterval();

            // We must run this task if:
            // * time() is larger or equal to $nextrun
            $run = (time() >= $nextrun);

            if ($run) {
                $this->output->writeln(sprintf('Running Cron Task <info>%s</info>', $crontask));

                // Set $lastrun for this crontask
                $crontask->setLastRun(new \DateTime());

                try {
                    $commands = $crontask->getCommands();
                    foreach ($commands as $command) {
                        $this->output->writeln(sprintf('Executing command <comment>%s</comment>...', $command));

                        // Run the command
                        $this->runCommand($command);
                    }
                    $this->output->writeln('<info>SUCCESS</info>');
                } catch (\Exception $e) {
                    $this->output->writeln('<error>ERROR</error>');
                    $this->getContainer()->get('monolog.logger.cron')->info("An exception occured while running the tasks: ".$e);
                }

                // Persist crontask
                $em->persist($crontask);
            } else {
                $this->output->writeln(sprintf('Skipping Cron Task <info>%s</info>', $crontask));
            }
        }

        // Flush database changes
        $em->flush();
    }

    private function runCommand($string)
    {
        // Split namespace and arguments
        $namespace = split(' ', $string)[0];

        // Set input
        $command = $this->getApplication()->find($namespace);
        $input = new StringInput($string);

        // Send all output to the console
        $returnCode = $command->run($input, $this->output);

        return $returnCode != 0;
    }
}

