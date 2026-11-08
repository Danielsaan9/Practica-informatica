<?php

namespace Glpi\Console\Build;

use Glpi\Application\Environment;
use Glpi\System\Diagnostic\SourceCodeIntegrityChecker;
use Glpi\Toolbox\VersionParser;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

use function Safe\file_put_contents;
use function Safe\json_encode;

class GenerateCodeManifestCommand extends Command
{
    protected function configure()
    {
        parent::configure();

        $this->setName('build:generate_code_manifest');
        $this->setDescription(__('Generate GLPI source code manifest that could be used to validate source code integrity.'));
        $this->addOption(
            'algorithm',
            'a',
            InputOption::VALUE_OPTIONAL,
            __('Hash algorithm to use'),
            'CRC32c'
        );
        $this->setHidden(!Environment::get()->shouldEnableExtraDevAndDebugTools());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $algorithm = $input->getOption('algorithm');

        try {
            $this->generateManifest($algorithm, $output);
            $output->writeln('<info>' . __('Manifest successfully generated.') . '</info>');
        } catch (Throwable $e) {
            $output->writeln('<error>' . sprintf(__('Failed to generate manifest. Error was: %s'), $e->getMessage()) . '</error>');
            return 1;
        }
        return 0;
    }

    private function generateManifest(string $algorithm, OutputInterface $output): void
    {
        $manifest_output = GLPI_ROOT . '/version/' . VersionParser::getNormalizedVersion(GLPI_VERSION, false);
        $checker = new SourceCodeIntegrityChecker();
        $manifest = $checker->generateManifest($algorithm);
        $manifest_json = json_encode($manifest, JSON_PRETTY_PRINT);
        $manifest_length = strlen($manifest_json);
        if (file_put_contents($manifest_output, $manifest_json) !== $manifest_length) {
            throw new RuntimeException(sprintf('Failed to write manifest to %s', $manifest_output));
        }
    }
}
