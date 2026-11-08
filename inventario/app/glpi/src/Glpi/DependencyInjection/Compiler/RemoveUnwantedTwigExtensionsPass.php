<?php

namespace Glpi\DependencyInjection\Compiler;

use Glpi\Application\Environment;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Unregister Twig extensions that Symfony TwigBundle auto-registers but that
 * GLPI does not want to activate yet.
 *
 * Some Symfony-provided extensions collide with GLPI extensions.
 * i.g. `twig.extension.routing` exposes `path` and `url` Twig functions,
 * which conflict with `Glpi\Application\View\Extension\RoutingExtension`.
 *
 */
final class RemoveUnwantedTwigExtensionsPass implements CompilerPassInterface
{
    /**
     * Twig extension tag to unload.
     * Can be listed by doing `bin/console symfony:debug:container --tag=twig.extension`
     */
    private const array UNWANTED_EXTENSION_IDS = [
        'twig.extension.routing',       // conflicts with Glpi\Application\View\Extension\RoutingExtension (path/url)
        'twig.extension.httpfoundation',
        'twig.extension.httpkernel',
        'twig.extension.assets',
        'twig.extension.form',
        'twig.extension.weblink',
        'twig.extension.serializer',
        'twig.extension.yaml',
        'twig.extension.expression',
        'twig.extension.emoji',
        'twig.extension.htmlsanitizer',
        'twig.extension.debug.stopwatch',
        'twig.extension.debug',
        'twig.extension.profiler',
        'workflow.twig_extension',
    ];

    private const array ALLOWED_IN_DEV = [
        'twig.extension.debug',
        'twig.extension.debug.stopwatch',
        'twig.extension.profiler',
        'twig.extension.httpkernel', // Required by the SF Profiler
    ];

    public function process(ContainerBuilder $container): void
    {
        foreach (self::UNWANTED_EXTENSION_IDS as $id) {
            // We allow debug extensions in dev env
            if (Environment::get()->shouldEnableExtraDevAndDebugTools()) {
                if (in_array($id, self::ALLOWED_IN_DEV)) {
                    continue;
                }
            }

            if ($container->hasDefinition($id)) {
                $container->getDefinition($id)->clearTag('twig.extension');
            }
        }
    }
}
