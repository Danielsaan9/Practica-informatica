<?php

namespace Twig\Components\Alert;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Default Alert component so users can <twig:Alert>...</twig:Alert>
 */
#[AsTwigComponent(name: 'Alert', template: 'twig_components/Alert/Base.html.twig')]
final class Alert extends AbstractAlert
{
    public string $color = 'info';
}
