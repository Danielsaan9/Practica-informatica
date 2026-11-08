<?php

class TicketSatisfaction extends CommonITILSatisfaction
{
    public static string $rightname = 'ticket';

    public static function getConfigSufix(): string
    {
        return "";
    }

    public static function getSearchOptionIDOffset(): int
    {
        return 0;
    }
}
