<?php

namespace Glpi\Stat;

/**
 * Data for front/stat.graph.php
 */
abstract class StatDataAlwaysDisplay extends StatData
{
    public function isEmpty(): bool
    {
        // Force display even if no data
        return false;
    }
}
