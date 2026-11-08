<?php

namespace Glpi\Application\View;

use Glpi\Debug\Profiler;
use Glpi\DependencyInjection\PublicService;
use Glpi\Kernel\Kernel;
use Twig\Environment as TwigEnvironment;

/**
 * @since 10.0.0
 */
class TemplateRenderer implements PublicService
{
    private TwigEnvironment $environment;

    public function __construct(TwigEnvironment $twig)
    {
        $this->environment = $twig;
    }

    /**
     * Return singleton instance of self.
     *
     * @return TemplateRenderer
     */
    public static function getInstance(): TemplateRenderer
    {
        /** @var Kernel $kernel */
        global $kernel;

        $renderer = $kernel->getContainer()->get(static::class);
        if (!$renderer instanceof TemplateRenderer) {
            throw new \RuntimeException('TemplateRenderer is expected to be registered as a service.');
        }

        return $renderer;
    }

    /**
     * Return Twig environment used to handle templates.
     *
     * @return TwigEnvironment
     * @internal
     */
    public function getEnvironment(): TwigEnvironment
    {
        return $this->environment;
    }

    /**
     * Renders a template.
     *
     * @param string $template
     * @param array  $variables
     *
     * @return string
     */
    public function render(string $template, array $variables = []): string
    {
        try {
            Profiler::getInstance()->start($template, Profiler::CATEGORY_TWIG);
            return $this->environment->load($template)->render($variables);
        } finally {
            Profiler::getInstance()->stop($template);
        }
    }

    /**
     * Displays a template.
     *
     * @param string $template
     * @param array  $variables
     *
     * @return void
     */
    public function display(string $template, array $variables = []): void
    {
        try {
            Profiler::getInstance()->start($template, Profiler::CATEGORY_TWIG);
            $this->environment->load($template)->display($variables);
        } finally {
            Profiler::getInstance()->stop($template);
        }
    }

    /**
     * Renders a template from a string.
     *
     * @param string $template
     * @param array  $variables
     *
     * @return string
     */
    public function renderFromStringTemplate(string $template, array $variables = []): string
    {
        try {
            Profiler::getInstance()->start($template, Profiler::CATEGORY_TWIG);
            return $this->environment->createTemplate($template)->render($variables);
        } finally {
            Profiler::getInstance()->stop($template);
        }
    }
}
