<?php
namespace Tasks\CustomCommand\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Greeting extends Command
{
    protected function configure()
    {
        $this->setName('greeting')
            ->setDescription('Displays a greeting');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Hello!</info>');
    }
}
