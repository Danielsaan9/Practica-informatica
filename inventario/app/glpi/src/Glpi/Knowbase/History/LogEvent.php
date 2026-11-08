<?php

namespace Glpi\Knowbase\History;

use Override;
use Safe\Exceptions\PcreException;

use function Safe\preg_match;

class LogEvent implements HistoryEventInterface
{
    public function __construct(
        private string $label,
        private string $description,
        private ?string $date,
        private ?string $author,
        private ?string $old_value = null,
        private ?string $new_value = null,
    ) {}

    #[Override]
    public function getLabel(): string
    {
        return $this->label;
    }

    #[Override]
    public function getDescription(): string
    {
        return $this->description;
    }

    #[Override]
    public function getDate(): ?string
    {
        return $this->date;
    }

    #[Override]
    public function getAuthor(): int
    {
        // Try to get the user ID from the raw text entry.
        // Not as easy as it seems, see LogEventTest::authorProvider() for all cases to cover.
        try {
            preg_match('/.*[(\x{FF08}]\s*([0-9]+)\s*[)\x{FF09}]/us', $this->author ?? '', $matches);
        } catch (PcreException $e) {
            // The /u flag can fail if the content is not valid UTF-8.
            // Prevent an unlikely failure by returning 0.
            // A warning will still be emitted here.
            return 0;
        }

        return (int) ($matches[1] ?? 0);
    }

    public function getNewValue(): ?string
    {
        return $this->new_value !== null ? strip_tags($this->new_value) : null;
    }

    public function getOldValue(): ?string
    {
        return $this->old_value !== null ? strip_tags($this->old_value) : null;
    }
}
