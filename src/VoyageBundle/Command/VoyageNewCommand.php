<?php

namespace VoyageBundle\Command;

use Composer\Command\BaseCommand;
use ReservationBundle\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use VoyageBundle\Controller\VoyageController;
use VoyageBundle\Controller\ReclamationVoyageController;
use Symfony\Component\HttpFoundation\Request;

class VoyageNewCommand extends ContainerAwareCommand
{
    private $V;

    public function __construct()
    {
        $this->V = new VoyageController();

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setName('Voyage:new')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }



    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $request = Request::createFromGlobals();
        $controller = new VoyageController();
        $controller->setContainer($this->getContainer()); // from the ContainerAwareCommand interface
        $controller->newAction();
        return 0;


    }

}
