<?php
namespace UR\AmburgerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CorrectionSessionInvalidateCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
            ->setName('correctionsession:invalidate')
            ->setDescription('Invalidates all old unused sessions.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Invalidating old sessions...</comment>');

        $this->output = $output;
        $em = $this->getContainer()->get('doctrine')->getManager('system');
        
        $sessions = $em->getRepository('AmburgerBundle:CorrectionSession')->findAll();
        
        $olderThan = time() - 3600; //older than an hour
        
        foreach($sessions as $session){
            $lastModified = $session->getModified() ? $session->getModified()->format('U') : 0;
            
            if($lastModified <= $olderThan){
                $output->writeln(sprintf('Removing correction session for OID: <info>%s</info>', $session->getOid()));
                
                //remove currently in progress flag from personData
                $personData = $em->getRepository('AmburgerBundle:PersonData')->findOneByOid($session->getOid());
                
                if(!is_null($personData)){
                    $personData->setCurrentlyInProcess(false);
                    $em->merge($personData);
                }
                
                $em->remove($session);
            }
        }

        // Flush database changes
        $em->flush();

        $output->writeln('<comment>Done!</comment>');
    }

   
    
    
}

