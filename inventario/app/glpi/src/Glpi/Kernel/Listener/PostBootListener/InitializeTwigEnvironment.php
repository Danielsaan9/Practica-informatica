<?php

namespace Glpi\Kernel\Listener\PostBootListener;

use Glpi\Application\Environment as GLPIEnvironment;
use Glpi\Kernel\Kernel;
use Glpi\Kernel\ListenersPriority;
use Glpi\Kernel\PostBootEvent;
use Plugin;
use Session;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;

final readonly class InitializeTwigEnvironment implements EventSubscriberInterface
{
    private string $glpi_root;
    private TwigEnvironment $twig_environment;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        string $glpi_root,
        TwigEnvironment $twig_environment
    ) {
        $this->glpi_root = $glpi_root;
        $this->twig_environment = $twig_environment;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PostBootEvent::class => ['onPostBoot', ListenersPriority::POST_BOOT_LISTENERS_PRIORITIES[self::class]],
        ];
    }

    public function onPostBoot(): void
    {
        $this->configureTwigEnvironment();
        $this->registerSpecificPaths();
    }

    private function configureTwigEnvironment(): void
    {
        $glpi_environment = GLPIEnvironment::get();

        $is_debug = $glpi_environment->shouldEnableExtraDevAndDebugTools() || ($_SESSION['glpi_use_mode'] ?? null) === Session::DEBUG_MODE;
        if ($is_debug) {
            $this->twig_environment->enableDebug();
        }

        $should_auto_reload = $glpi_environment->shouldExpectResourcesToChange();
        if ($should_auto_reload) {
            $this->twig_environment->enableAutoReload();
        }

        if (GLPI_STRICT_ENV) {
            $this->twig_environment->enableStrictVariables();
        }

        $cachedir = Kernel::getCacheRootDir();
        $tpl_cachedir = $cachedir . '/templates';
        if (
            (file_exists($tpl_cachedir) && !is_writable($tpl_cachedir))
            || (!file_exists($tpl_cachedir) && !is_writable($cachedir))
        ) {
            trigger_error(sprintf('Cache directory "%s" is not writeable.', $tpl_cachedir), E_USER_WARNING);
            $this->twig_environment->setCache(false);
        } else {
            $this->twig_environment->setCache($tpl_cachedir);
        }
    }

    /**
     * Mount plugin template namespaces and test templates onto the DI twig env's loader.
     *
     * The TwigBundle filesystem loader already has the main templates/ path.
     * We extend it at runtime with per-plugin namespaces and (in test mode) the test namespace.
     */
    private function registerSpecificPaths(): void
    {
        $loader = $this->twig_environment->getLoader();
        if (!$loader instanceof FilesystemLoader) {
            return;
        }

        $active_plugins = Plugin::getPlugins();
        foreach ($active_plugins as $plugin_key) {
            $path = Plugin::getPhpDir($plugin_key);
            if (!$path) {
                continue;
            }

            $path .= '/templates';
            if (is_dir($path)) {
                // `@my_plugin/path/to/template.html.twig` where `my_plugin` is the plugin key and `path/to/template.html.twig`
                // is the path of the template inside the `/templates` directory of the plugin.
                $loader->addPath($path, $plugin_key);
            }
        }

        $glpi_environment = GLPIEnvironment::get();
        if (
            $glpi_environment->shouldEnableTestResources()
            && \file_exists("$this->glpi_root/tests/templates")
        ) {
            // Add a dedicated namespace for specific test templates.
            // For instance `@test/path/to/template.html.twig`
            $loader->addPath($this->glpi_root . '/tests/templates', 'test');
        }
    }
}
