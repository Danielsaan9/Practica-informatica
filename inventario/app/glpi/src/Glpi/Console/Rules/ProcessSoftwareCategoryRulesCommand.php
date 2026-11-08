<?php

namespace Glpi\Console\Rules;

use Glpi\Console\AbstractCommand;
use RuleSoftwareCategoryCollection;
use Software;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessSoftwareCategoryRulesCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('rules:process_software_category_rules');
        $this->setDescription(__('Process software category rules'));

        $this->addOption(
            'all',
            'a',
            InputOption::VALUE_NONE,
            __('Process rule for all software, even those having already a defined category')
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $query = [
            'SELECT' => [
                'id',
            ],
            'FROM'   => Software::getTable(),
        ];
        if (!$input->getOption('all')) {
            $query['WHERE'] = [
                'softwarecategories_id' => 0,
            ];
        }

        $software_iterator = $this->getDb()->request($query);

        $sofware_count = $software_iterator->count();
        if ($sofware_count === 0) {
            $output->writeln('<info>' . __('No software to process.') . '</info>');
            return 0; // Success
        }

        $progress_bar = new ProgressBar($output, $sofware_count);
        $progress_bar->start();

        $processed_count = 0;
        foreach ($software_iterator as $data) {
            $progress_bar->advance(1);

            $this->writelnOutputWithProgressBar(
                sprintf(__('Processing software having id "%s".'), $data['id']),
                $progress_bar,
                OutputInterface::VERBOSITY_VERY_VERBOSE
            );

            $software = new Software();

            if (!$software->getFromDB($data['id'])) {
                $this->writelnOutputWithProgressBar(
                    sprintf(__('Unable to load software having id "%s".'), $data['id']),
                    $progress_bar,
                    OutputInterface::VERBOSITY_NORMAL
                );
                continue;
            }

            $rule_collection = new RuleSoftwareCategoryCollection();
            $input = $rule_collection->processAllRules(
                [],
                $software->fields,
                [
                    'name'             => $software->fields['name'],
                    'manufacturers_id' => $software->fields['manufacturers_id'],
                ]
            );

            $software->update($input);

            $processed_count++;
        }

        $progress_bar->finish();
        $this->output->write(PHP_EOL);

        $output->writeln(
            '<info>' . sprintf(__('Number of software processed: %d.'), $processed_count) . '</info>'
        );

        return 0; // Success
    }
}
