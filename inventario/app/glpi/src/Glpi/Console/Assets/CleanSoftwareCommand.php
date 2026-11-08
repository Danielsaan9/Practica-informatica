<?php

namespace Glpi\Console\Assets;

use CleanSoftwareCron;
use Glpi\Console\AbstractCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CleanSoftwareCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('assets:cleansoftware');
        $this->setDescription(CleanSoftwareCron::getTaskDescription());

        $this->addOption(
            'max',
            'm',
            InputOption::VALUE_REQUIRED,
            CleanSoftwareCron::getParameterDescription(),
            500
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->validateInput($input);
        $max = $input->getOption('max');

        // Run crontask
        $total = CleanSoftwareCron::run($max);
        $output->writeln("<info> $total item(s) deleted </info>");

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
