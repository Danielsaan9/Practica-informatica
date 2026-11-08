<?php

namespace Twig\Components\Alert;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'twig_components/Alert/Base.html.twig')]
final class Warning extends AbstractAlert
{
    public string $color = 'warning';
}
