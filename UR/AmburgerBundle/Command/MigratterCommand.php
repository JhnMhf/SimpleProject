<?php
namespace UR\AmburgerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class MigratterCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
            ->setName('personmigration:run')
            ->setDescription('Migrate unmigratted persons from old to new db.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //TODO: prevent parallel running?
        $this->getContainer()->get('monolog.logger.cron')->info("Running the migratter crontask.");
        $startTime = time();
        $output->writeln('<comment>Migrating persons from old to new...</comment>');

        $this->output = $output;
        
        $this->getContainer()->get("migration_process.service")->run();
        
        
        $duration = time() - $startTime;

        $output->writeln(sprintf('<comment>Done it took %s seconds.</comment>', $duration));
    }

   
    
    
}

