<?php

namespace Glpi\Console\Build;

use Html;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use Safe\Exceptions\FilesystemException;
use SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Toolbox;

use function Safe\file_put_contents;
use function Safe\mkdir;
use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\realpath;

class CompileScssCommand extends Command
{
    /**
     * Error code returned if unable to write compiled CSS.
     *
     * @var int
     */
    public const ERROR_UNABLE_TO_WRITE_COMPILED_FILE = 1;

    protected function configure()
    {
        parent::configure();

        $this->setName('build:compile_scss');
        $this->setDescription('Compile SCSS file.');

        $this->addOption(
            'file',
            'f',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'File to compile (compile all SCSS files by default)'
        );

        $this->addOption(
            'dry-run',
            null,
            InputOption::VALUE_NONE,
            'Simulate compilation without actually save compiled CSS files'
        );
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {

        $compile_directory = Html::getScssCompileDir();

        if (!@is_dir($compile_directory)) {
            try {
                @mkdir($compile_directory);
            } catch (FilesystemException $e) {
                throw new RuntimeException(
                    sprintf(
                        'Destination directory "%s" cannot be accessed.',
                        $compile_directory
                    ),
                    $e->getCode(),
                    $e
                );
            }
        }

        // Ensure to have enough memory to not reach memory limit.
        $max_memory = Html::MAIN_SCSS_COMPILATION_REQUIRED_MEMORY;
        if (Toolbox::getMemoryLimit() < $max_memory) {
            Toolbox::safeIniSet('memory_limit', $max_memory);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $files = $input->getOption('file');
        $dry_run = $input->getOption('dry-run');

        if (empty($files)) {
            $root_path = str_replace(DIRECTORY_SEPARATOR, '/', realpath(GLPI_ROOT));

            $css_dir_iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($root_path . '/css'),
                RecursiveIteratorIterator::SELF_FIRST
            );
            /** @var SplFileInfo $file */
            foreach ($css_dir_iterator as $file) {
                $file_path = str_replace(DIRECTORY_SEPARATOR, '/', $file->getPath());
                if (
                    !$file->isReadable() || !$file->isFile() || $file->getExtension() !== 'scss'
                     || preg_match('/^' . preg_quote($root_path . '/css/lib/', '/') . '/', $file_path) === 1
                     || preg_match('/^_/', $file->getBasename()) === 1
                ) {
                    continue;
                }

                $dir_path = str_replace(DIRECTORY_SEPARATOR, '/', dirname($file->getRealPath()));
                $files[] = str_replace($root_path . '/', '', $dir_path)
                    . '/'
                    . preg_replace('/^_?(.*)\.scss$/', '$1', $file->getBasename());
            }
        }

        foreach ($files as $file) {
            $output->writeln(
                '<comment>' . sprintf('Processing "%s".', $file) . '</comment>',
                OutputInterface::VERBOSITY_VERBOSE
            );

            $compiled_path = Html::getScssCompilePath($file);
            $css = Html::compileScss(
                [
                    'file'    => $file,
                    'nocache' => true,
                ]
            );

            if ($dry_run) {
                $message = sprintf('"%s" compiled successfully.', $file);
                $output->writeln(
                    '<info>' . $message . '</info>',
                    OutputInterface::VERBOSITY_NORMAL
                );
            } else {
                try {
                    @file_put_contents($compiled_path, $css);
                    $message = sprintf('"%s" compiled successfully in "%s".', $file, $compiled_path);
                    $output->writeln(
                        '<info>' . $message . '</info>',
                        OutputInterface::VERBOSITY_NORMAL
                    );
                } catch (FilesystemException $e) {
                    $message = sprintf('Unable to write compiled CSS in "%s".', $compiled_path);
                    $output->writeln(
                        '<error>' . $message . '</error>',
                        OutputInterface::VERBOSITY_QUIET
                    );
                    return self::ERROR_UNABLE_TO_WRITE_COMPILED_FILE;
                }
            }
        }

        return 0; // Success
    }
}
