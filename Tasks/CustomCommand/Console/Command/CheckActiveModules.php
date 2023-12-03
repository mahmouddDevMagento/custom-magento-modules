<?php
namespace Tasks\CustomCommand\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Module\ModuleListInterface;

class CheckActiveModules extends Command
{
    /**
     * @var ModuleListInterface
     */
    protected $moduleList;

    public function __construct(ModuleListInterface $moduleList)
    {
        $this->moduleList = $moduleList;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('check-active')
            ->setDescription('Display list of active modules');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $modules = $this->moduleList->getNames();

        if (count($modules) > 0) {
            $output->writeln('<info>Active Modules:</info>');
            foreach ($modules as $moduleName) {
                $output->writeln($moduleName);
            }
        } else {
            $output->writeln('<info>No active modules found.</info>');
        }
    }
}

