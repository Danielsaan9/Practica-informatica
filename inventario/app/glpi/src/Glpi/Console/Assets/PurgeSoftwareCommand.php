<?php

namespace Glpi\Console\Assets;

use Glpi\Console\AbstractCommand;
use PurgeSoftwareTask;
use Software;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PurgeSoftwareCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('assets:purgesoftware');
        $this->setDescription(Software::getPurgeTaskDescription());

        $this->addOption(
            'max',
            'm',
            InputOption::VALUE_REQUIRED,
            Software::getPurgeTaskParameterDescription(),
            500
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->validateInput($input);
        $max = $input->getOption('max');

        $task = new PurgeSoftwareTask();
        $total = $task->run($max);
        $output->writeln('<info>' . sprintf(__('%s item(s) removed from the database.'), $total) . '</info>');

        return 0;
    }

    /**
     * Validate command input.
     *
     * @param InputInterface $input
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateInput(InputInterface $input)
    {
        $max = $input->getOption('max');
        if (!is_numeric($max)) {
            throw new InvalidArgumentException(
                __('Option --max must be an integer.')
            );
        }
    }
}
