<?php
namespace UR\AmburgerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class PersonDataCreateCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
            ->setName('createpersondata:run')
            ->setDescription('Creates missing person data objects in SystemDB for entries of the FinalDB.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $this->getContainer()->get('monolog.logger.default')->info("Running the PersonDataCreator command.");
        $startTime = time();
        $output->writeln('<comment>Creating missing person data.</comment>');

        $this->output = $output;
        
        $this->getContainer()->get("person_data_creator.service")->createMissingEntries();
        
        
        $duration = time() - $startTime;

        $output->writeln(sprintf('<comment>Done it took %s seconds.</comment>', $duration));
    }

   
    
    
}

