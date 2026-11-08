<?php

namespace Glpi\Debug;

use Ramsey\Uuid\Uuid;

final class ProfilerSection
{
    private string $id;

    private ?string $parent_id;

    private string $category;

    private string $name;

    private int $start;

    private ?int $end = null;

    /**
     * @var array{start: int, end?: int}[] Array of start and end times of paises which will be removed from the final duration.
     */
    private array $pauses = [];

    /**
     * @param string $category
     * @param string $name
     * @param int $start
     * @param ?string $parent_id
     * @param ?string $id
     */
    public function __construct(string $category, string $name, $start, ?string $parent_id = null, ?string $id = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->parent_id = $parent_id;
        $this->category = $category;
        $this->name = $name;
        $this->start = (int) $start;
    }

    /**
     * @param int $time
     *
     * @return void
     */
    public function end($time): void
    {
        // Force resume to complete the last pause.
        $this->resume();
        $this->end = (int) $time;
    }

    public function getID(): string
    {
        return $this->id;
    }

    public function getParentID(): ?string
    {
        return $this->parent_id;
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getEnd(): int
    {
        return $this->end;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDuration(): int
    {
        $end = $this->end ?? (int) (microtime(true) * 1000);
        $duration = $end - $this->start;

        // Remove paused time from the total runtime.
        foreach ($this->pauses as $pause) {
            $pause_end = $pause['end'] ?? $end;
            $duration -= $pause_end - $pause['start'];
        }

        return (int) $duration;
    }

    public function isFinished(): bool
    {
        return $this->end !== null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'category' => $this->category,
            'name' => $this->name,
            'start' => $this->start,
            'end' => $this->end,
            'duration' => $this->getDuration(),
        ];
    }

    public static function fromArray(array $array): self
    {
        $section = new self($array['category'], $array['name'], $array['start'], $array['parent_id'], $array['id']);
        $section->end($array['end']);
        return $section;
    }

    public function pause(): void
    {
        if (!$this->isPaused()) {
            $this->pauses[] = ['start' => microtime(true) * 1000];
        }
    }

    public function resume(): void
    {
        if (!$this->isPaused()) {
            // Not paused. Ignore.
            return;
        }
        $last_pause = array_key_last($this->pauses);
        $this->pauses[$last_pause]['end'] = microtime(true) * 1000;
    }

    public function isPaused(): bool
    {
        if (!count($this->pauses)) {
            return false;
        }
        $last_pause = end($this->pauses);
        return count($last_pause) === 1;
    }
}
