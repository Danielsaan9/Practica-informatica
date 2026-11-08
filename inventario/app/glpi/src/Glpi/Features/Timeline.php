<?php

namespace Glpi\Features;

/**
 * Trait Kanban.
 * @since 9.5.0
 */
trait Timeline
{
    abstract public function getTimelineItemtypes(): array;
}
