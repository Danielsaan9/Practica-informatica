<?php

namespace Glpi\Knowbase\History;

final class CurrentVersionLogEvent extends LogEvent
{
    public function __construct(
        ?string $date,
        ?string $author,
    ) {
        parent::__construct(
            label: __("Current version"),
            description: __("Updated by"),
            date: $date,
            author: $author,
        );
    }
}
