<?php

namespace Glpi\Console\Traits;

use Glpi\Message\MessageType;
use Glpi\Migration\PluginMigrationResult;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @phpstan-ignore trait.unused
 */
trait PluginMigrationTrait
{
    /**
     * Output the plugin migration result.
     */
    protected function outputPluginMigrationResult(OutputInterface $output, PluginMigrationResult $result): void
    {
        if (!$result->isFullyProcessed()) {
            $output->writeln('<error>' . __('The migration failed.') . '</error>');
        } elseif ($result->hasErrors()) {
            $output->writeln('<comment>' . __('The migration is complete, but errors have occurred.') . '</comment>');
        } else {
            $output->writeln('<comment>' . __('The migration is complete.') . '</comment>');
        }

        $messages = $result->getMessages();

        if ($result->isFullyProcessed()) {
            $created_items_ids = $result->getCreatedItemsIds();
            $created_count = \array_reduce($created_items_ids, static fn(int $count, array $ids): int => $count + count($ids), 0);
            if ($created_count > 0) {
                $messages[] = [
                    'type'    => MessageType::Success,
                    'message' => sprintf(__('%1$d items created.'), $created_count),
                ];
            }

            $reused_items_ids = $result->getReusedItemsIds();
            $reused_count = \array_reduce($reused_items_ids, static fn(int $count, array $ids): int => $count + count($ids), 0);
            if ($reused_count > 0) {
                $messages[] = [
                    'type'    => MessageType::Success,
                    'message' => sprintf(__('%1$d items reused.'), $reused_count),
                ];
            }

            $ignored_items_ids = $result->getIgnoredItemsIds();
            $ignored_count = \array_reduce($ignored_items_ids, static fn(int $count, array $ids): int => $count + count($ids), 0);
            if ($ignored_count > 0) {
                $messages[] = [
                    'type'    => MessageType::Notice,
                    'message' => sprintf(__('%1$d items ignored.'), $ignored_count),
                ];
            }
        } else {
            $messages[] = [
                'type'    => MessageType::Error,
                'message' => __("Migration was aborted due to errors, all changes have been reverted."),
            ];
        }

        foreach ($messages as $entry) {
            match ($entry['type']) {
                MessageType::Error => $output->writeln('<error>x</error> ' . $entry['message'], OutputInterface::VERBOSITY_QUIET),
                MessageType::Warning => $output->writeln('<comment>⚠</comment> ' . $entry['message'], OutputInterface::VERBOSITY_NORMAL),
                MessageType::Success => $output->writeln('<info>✓</info> ' . $entry['message'], OutputInterface::VERBOSITY_NORMAL),
                MessageType::Notice => $output->writeln('🛈 ' . $entry['message'], OutputInterface::VERBOSITY_NORMAL),
                MessageType::Debug => $output->writeln('[DEBUG] ' . $entry['message'], OutputInterface::VERBOSITY_VERY_VERBOSE),
            };
        }
    }
}
