<?php

namespace Twig\Components\Alert;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

// No name needed, it will match to Alert:Info
#[AsTwigComponent(template: 'twig_components/Alert/Base.html.twig')]
final class Info extends AbstractAlert
{
    public string $color = 'info';
}
