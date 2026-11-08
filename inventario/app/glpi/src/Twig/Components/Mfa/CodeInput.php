<?php

namespace Twig\Components\Mfa;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Segmented one-time-code field (one input per digit), usable as <twig:Mfa:CodeInput />.
 */
#[AsTwigComponent()]
final class CodeInput
{
    public int $digits = 6;
}
