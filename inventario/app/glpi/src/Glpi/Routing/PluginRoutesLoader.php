<?php

namespace Glpi\Routing;

use Plugin;
use Symfony\Bundle\FrameworkBundle\Routing\AttributeRouteControllerLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Loader\AttributeDirectoryLoader;
use Symfony\Component\Routing\RouteCollection;

class PluginRoutesLoader extends Loader
{
    public function load(mixed $resource, ?string $type = null): RouteCollection
    {
        $routes = new RouteCollection();

        $plugins = Plugin::getPlugins();

        foreach ($plugins as $plugin_key) {
            $plugin_path = Plugin::getPhpDir($plugin_key) . '/src/Controller/';

            if (!\file_exists($plugin_path)) {
                // No controller directory found in the plugin
                continue;
            }

            $loader = new AttributeDirectoryLoader(
                new FileLocator($plugin_path),
                new AttributeRouteControllerLoader($this->env),
            );
            $plugin_routes = $loader->load($plugin_path, 'attribute');

            if ($plugin_routes->count() === 0) {
                // No route found in the plugin
                continue;
            }

            $name_prefix_mapping = [
                'plugins'     => sprintf('@%s:', $plugin_key),
                'marketplace' => sprintf('@%s_marketplace:', $plugin_key),
            ];

            foreach ($name_prefix_mapping as $plugin_dir => $name_prefix) {
                $prefixed_plugin_routes = clone $plugin_routes;
                $prefixed_plugin_routes->addPrefix(sprintf('/%s/%s/', $plugin_dir, $plugin_key));
                $prefixed_plugin_routes->addNamePrefix($name_prefix);
                $routes->addCollection($prefixed_plugin_routes);
            }
        }

        return $routes;
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return $resource === 'glpi_routes';
    }
}
