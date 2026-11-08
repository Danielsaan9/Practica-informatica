<?php

namespace Glpi\Application\View\Extension;

use Html;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @since 11.0.0
 */
class HtmlExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('safe_dom_name', Html::sanitizeInputName(...)),
            new TwigFilter('safe_dom_id', Html::sanitizeDomId(...)),
        ];
    }
}
