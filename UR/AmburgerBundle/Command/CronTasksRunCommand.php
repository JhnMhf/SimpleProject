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
    //43200 would be 12 hours
    //3600 would be one hour
    const RESET_TIMEOUT_IN_SECONDS = 900;
    
    private $output;

    protected function configure()
    {
        $this
            ->setName('crontasks:run')
            ->setDescription('Runs Cron Tasks if needed');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->getContainer()->get('monolog.logger.cron')->info("Running cron tasks...");
        $this->writeToOutput('<comment>Running Cron Tasks...</comment>');

        
        $em = $this->getContainer()->get('doctrine')->getManager('system');
        
        if(!$this->startRun($em)){
            return;
        }
        
        $this->runTasks($em);
        
        $this->finishRun($em);

        $this->writeToOutput('<comment>Done!</comment>');
    }
    
    private function startRun($em){
        //add option to deactivate runner itself, not only tasks
        $taskWorkers = $em->getRepository('AmburgerBundle:TaskWorker')->findAll();
        
        if(is_null($taskWorkers) || count($taskWorkers) == 0){
            $newTaskWorker = new TaskWorker();
            $newTaskWorker->setRunning(true);
            $newTaskWorker->setLastRun(new \DateTime());
            
            $em->persist($newTaskWorker);
        } else if(!is_null($taskWorkers) && count($taskWorkers) == 1 && !$taskWorkers[0]->getRunning()){
            $taskWorkers[0]->setRunning(true);
            $taskWorkers[0]->setLastRun(new \DateTime());
            
            $em->merge($taskWorkers[0]);
        } else {
            $lastrun = $taskWorkers[0]->getLastRun() ? $taskWorkers[0]->getLastRun()->format('U') : 0;
            
            $resetTime = $lastrun + CronTasksRunCommand::RESET_TIMEOUT_IN_SECONDS;

            // We must reset the runner:
            $reset = (time() >= $resetTime);
            
            if($reset){
                $this->writeToOutput("<comment>The previous runner somehow didn't stop. Restarting the worker.</comment>");
                $this->getContainer()->get("monolog.logger.cron")->info("The previous runner somehow didn't stop. Restarting the worker.");
                $taskWorkers[0]->setRunning(true);
                $taskWorkers[0]->setLastRun(new \DateTime());
                $em->merge($taskWorkers[0]);
            }else {
                $this->writeToOutput('<comment>Previous worker is still running. Skipping this run.</comment>');
                $this->getContainer()->get('monolog.logger.cron')->info("Previous worker is still running. Skipping this run.");
                return false;
            }
        }
        
        $em->flush();
        
        return true;
    }
    
    private function writeToOutput($string){
        $this->output->writeln($this->currentDateAsString()." ".$string);
    }
    
    private function currentDateAsString(){
        $date = new \DateTime();
                
        return $date->format('Y-m-d H:i:s');
    }
    
    private function finishRun($em){
        $taskWorkers = $em->getRepository('AmburgerBundle:TaskWorker')->findAll();
        $taskWorkers[0]->setRunning(false);
        $taskWorkers[0]->setLastRun(new \DateTime());
        $em->merge($taskWorkers[0]);
        $em->flush();
    }
    
    private function runTasks($em){
        $crontasks = $em->getRepository('AmburgerBundle:CronTask')->findAll();

        foreach ($crontasks as $crontask) {
            if($crontask->getActive()){
                // Get the last run time of this task, and calculate when it should run next
                $lastrun = $crontask->getLastRun() ? $crontask->getLastRun()->format('U') : 0;
                $nextrun = $lastrun + $crontask->getRunInterval();

                // We must run this task if:
                // * time() is larger or equal to $nextrun
                $run = (time() >= $nextrun);

                if ($run) {
                    $this->writeToOutput(sprintf('Running Cron Task <info>%s</info>', $crontask));

                    // Set $lastrun for this crontask
                    $crontask->setLastRun(new \DateTime());

                    try {
                        $commands = $crontask->getCommands();
                        foreach ($commands as $command) {
                            $this->writeToOutput(sprintf('Executing command <comment>%s</comment>...', $command));

                            // Run the command
                            $this->runCommand($command);
                        }
                        $this->writeToOutput('<info>SUCCESS</info>');
                    } catch (\Exception $e) {
                        $this->writeToOutput('<error>ERROR</error>');
                        $this->getContainer()->get('monolog.logger.cron')->info("An exception occured while running the tasks: ".$e);
                    }

                    // Persist crontask
                    $em->persist($crontask);
                } else {
                    $this->writeToOutput(sprintf('Skipping Cron Task <info>%s</info>', $crontask));
                }
            }else {
                $this->writeToOutput(sprintf('Cron Task <info>%s</info> is deactivated', $crontask));
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

