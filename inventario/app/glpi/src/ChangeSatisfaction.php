<?php

class ChangeSatisfaction extends CommonITILSatisfaction
{
    public static string $rightname = 'change';

    public static function getConfigSufix(): string
    {
        return "_change";
    }

    public static function getSearchOptionIDOffset(): int
    {
        return 200;
    }
}
