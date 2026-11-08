<?php

namespace Glpi\Knowbase\History;

use LogicException;

final class HistoryEventList
{
    /** @param HistoryEventInterface[] $events */
    public function __construct(
        private array $events = [],
    ) {}

    public function addEvent(HistoryEventInterface $event): void
    {
        $this->events[] = $event;
    }

    /** @return HistoryEventInterface[] */
    public function getEvents(): array
    {
        return $this->events;
    }

    public function count(): int
    {
        return \count($this->events);
    }

    public function slice(int $offset, int $limit): self
    {
        return new self(\array_slice($this->events, $offset, $limit));
    }

    public function getLatestEvent(): HistoryEventInterface
    {
        $key = array_key_first($this->events);
        if ($key === null) {
            throw new LogicException("Item has no history events");
        }

        return $this->events[$key];
    }

    public function sort(): void
    {
        usort(
            $this->events,
            static function (HistoryEventInterface $left, HistoryEventInterface $right): int {
                $date_cmp = strcmp($right->getDate() ?? '', $left->getDate() ?? '');
                if ($date_cmp !== 0) {
                    return $date_cmp;
                }

                // When dates are equal, LogEvents (metadata changes) come
                // before RevisionEvents (content snapshots).
                return ($left instanceof RevisionEvent ? 1 : 0)
                    - ($right instanceof RevisionEvent ? 1 : 0);
            }
        );
    }
}
