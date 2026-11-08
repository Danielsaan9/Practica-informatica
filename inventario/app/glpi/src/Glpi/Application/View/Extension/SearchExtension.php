<?php

namespace Glpi\Application\View\Extension;

use Glpi\Search\Output\HTMLSearchOutput;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @since 10.0.0
 */
class SearchExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('showItem', [$this, 'showItem'], ['is_safe' => ['html']]),
        ];
    }

    public function showItem(
        int $displaytype,
        ?string $value = null,
        int $num = 0,
        int $row = 0,
        string $extraparams = ""
    ): string {
        // This is mandatory as HTMLSearchOutput::showItem expected second param to be passed by reference...
        $output = new HTMLSearchOutput();
        return $output->showItem($value, $num, $row, $extraparams);
    }
}
