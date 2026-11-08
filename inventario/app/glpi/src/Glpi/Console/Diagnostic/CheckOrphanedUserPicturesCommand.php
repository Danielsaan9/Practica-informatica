<?php

namespace Glpi\Console\Diagnostic;

use Glpi\Console\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use User;

final class CheckOrphanedUserPicturesCommand extends AbstractCommand
{
    private const PICTURE_OK = 0;
    private const ERROR_MISSING_FILE = 1;

    protected function configure(): void
    {
        parent::configure();

        $this->setName('diagnostic:check_orphaned_user_pictures');
        $this->setDescription(__('Check user picture references that no longer exist on the filesystem.'));

        $this->addOption(
            'fix',
            null,
            InputOption::VALUE_NONE,
            __('Clear orphaned picture references from the database.')
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fix = $input->getOption('fix');

        $has_error = false;

        $progress_message = (fn(array $row) => sprintf(
            __('Checking user #%s "%s" (%s)...'),
            $row['id'],
            $row['name'],
            $row['picture']
        ));

        $count = $this->countUsers();
        foreach ($this->iterate($this->getUsers(), $progress_message, $count) as $row) {
            $status = $this->validatePicture($row);

            if ($status !== self::PICTURE_OK) {
                $this->outputMessage(
                    '<error>' . $this->getDetailedError($status, $row) . '</error>',
                    OutputInterface::VERBOSITY_QUIET
                );
                $has_error = true;

                if ($fix) {
                    global $DB;
                    $DB->update(
                        User::getTable(),
                        ['picture' => null],
                        ['id' => $row['id']]
                    );
                }
            }
        }

        if ($fix && $has_error) {
            $this->outputMessage('<info>' . __('Orphaned picture references have been cleared.') . '</info>');
        }

        return $has_error ? Command::FAILURE : Command::SUCCESS;
    }

    /**
     * @return iterable<array{id: int|string, name: string, picture: string}>
     */
    protected function getUsers(): iterable
    {
        global $DB;

        $i = 0;

        do {
            $rows = $DB->request([
                'SELECT' => ['id', 'name', 'picture'],
                'FROM'   => User::getTable(),
                'WHERE'  => [
                    'NOT' => ['picture' => null],
                    ['NOT' => ['picture' => '']],
                ],
                'LIMIT'  => 1000,
                'OFFSET' => $i * 1000,
            ]);
            yield from $rows;

            $i++;
        } while (count($rows) > 0);
    }

    protected function countUsers(): int
    {
        return countElementsInTable(User::getTable(), [
            'NOT' => ['picture' => null],
            ['NOT' => ['picture' => '']],
        ]);
    }

    /**
     * @param array<string, mixed> $row
     */
    protected function validatePicture(array $row): int
    {
        $path = GLPI_PICTURE_DIR . '/' . $row['picture'];

        if (!file_exists($path)) {
            return self::ERROR_MISSING_FILE;
        }

        return self::PICTURE_OK;
    }

    /**
     * @param array<string, mixed> $row
     */
    protected function getDetailedError(int $type, array $row): string
    {
        $message = match ($type) {
            self::ERROR_MISSING_FILE => __('File not found'),
            default                  => __('Unknown error'),
        };

        return sprintf(
            '%s #%s "%s" (%s): %s.',
            User::getTypeName(1),
            $row['id'],
            $row['name'],
            $row['picture'],
            $message
        );
    }
}
