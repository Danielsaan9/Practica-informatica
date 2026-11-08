<?php

namespace Glpi\Routing\Attribute;

use Attribute;
use CommonDBTM;
use Symfony\Component\Routing\Attribute\Route;

use function Safe\preg_replace;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class ItemtypeListRoute extends Route
{
    /**
     * @phpstan-param class-string<CommonDBTM> $itemtype
     */
    public function __construct(string $itemtype)
    {
        $path = $itemtype::getSearchUrl(false);

        if (\isPluginItemType($itemtype)) {
            // Plugin routes path should not contain the `/plugins/{plugin_key}` prefix that is added automatically.
            // @see `\Glpi\Router\PluginRoutesLoader::load()`
            $path = preg_replace('#^/plugins/[^/]+(/.*)?$#', '$1', $path);
        }

        parent::__construct(
            path: $path,
            name: 'glpi_itemtype_' . \strtolower($itemtype) . '_list',
        );
    }
}
