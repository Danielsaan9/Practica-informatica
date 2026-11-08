<?php

namespace Glpi\Application\View\Extension;

use Glpi\UI\IllustrationManager;
use Override;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class IllustrationExtension extends AbstractExtension
{
    private IllustrationManager $illustration_manager;

    public function __construct()
    {
        $this->illustration_manager = new IllustrationManager();
    }

    #[Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_illustration', [$this, 'renderIllustration'], [
                'is_safe' => ['html'],
            ]),
            new TwigFunction('render_scene', [$this, 'renderScene'], [
                'is_safe' => ['html'],
            ]),
            new TwigFunction(
                'searchIcons',
                [$this->illustration_manager, 'searchIcons'],
            ),
            new TwigFunction(
                'countIcons',
                [$this->illustration_manager, 'countIcons'],
            ),
            new TwigFunction(
                'getIconTitle',
                [$this->illustration_manager, 'getIconTitle'],
            ),
        ];
    }

    public function renderIllustration(string $filename, ?int $size = null): string
    {
        return $this->illustration_manager->renderIcon($filename, $size);
    }

    public function renderScene(
        string $filename,
        ?int $height = null,
        ?int $width = null,
    ): string {
        return $this->illustration_manager->renderScene($filename, $height, $width);
    }
}
