<?php

namespace Twig\Components\Alert;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'twig_components/Alert/Base.html.twig')]
final class Danger extends AbstractAlert
{
    public string $color = 'danger';
}
