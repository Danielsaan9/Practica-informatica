<?php

namespace Glpi\Search\Output;

use Entity;
use Glpi\Toolbox\URL;
use Location;
use Ticket;
use Toolbox;

/**
 *
 * @internal Not for use outside {@link Search} class and the "Glpi\Search" namespace.
 */
final class MapSearchOutput extends HTMLSearchOutput
{
    public static function prepareInputParams(string $itemtype, array $params): array
    {
        $params = parent::prepareInputParams($itemtype, $params);

        if ($itemtype === 'Location') {
            $latitude = 21;
            $longitude = 20;
        } elseif ($itemtype === 'Entity') {
            $latitude = 67;
            $longitude = 68;
        } else {
            $latitude = 998;
            $longitude = 999;
        }

        $params['criteria'][] = [
            'link'         => 'AND NOT',
            'field'        => $latitude,
            'searchtype'   => 'contains',
            'value'        => 'NULL',
            '_hidden'      => true,
        ];
        $params['criteria'][] = [
            'link'         => 'AND NOT',
            'field'        => $longitude,
            'searchtype'   => 'contains',
            'value'        => 'NULL',
            '_hidden'      => true,
        ];

        return $params;
    }

    public function displayData(array $data, array $params = []): void
    {
        global $CFG_GLPI;

        $itemtype = $data['itemtype'];
        if (isset($data['data']['totalcount']) && $data['data']['totalcount'] > 0) {
            $target = URL::sanitizeURL($data['search']['target']);
            $criteria = $data['search']['criteria'];
            array_pop($criteria);
            array_pop($criteria);
            $criteria[] = [
                'link'         => 'AND',
                'field'        => ($itemtype === Location::class || $itemtype === Entity::class) ? 1 : (($itemtype === Ticket::class) ? 83 : 3),
                'searchtype'   => 'equals',
                'value'        => 'CURLOCATION',
            ];

            $parameters = Toolbox::append_params(
                [
                    'as_map'       => 0,
                    'criteria'     => $criteria,
                    'metacriteria' => $data['search']['metacriteria'],
                    'sort'         => $data['search']['sort'],
                    'order'        => $data['search']['order'],
                ]
            );
            if (!str_contains($target, '?')) {
                $fulltarget = $target . "?" . $parameters;
            } else {
                $fulltarget = $target . "&" . $parameters;
            }

            $typename = class_exists($itemtype) ? $itemtype::getTypeName($data['data']['totalcount']) : $itemtype;

            $twig_params = [
                'ajax_url' => $CFG_GLPI['root_doc'] . '/ajax/map.php',
                'params'   => $params,
                'fulltarget' => $fulltarget,
                'typename' => $typename,
                'itemtype' => $itemtype,
            ];
        }
        parent::displayData($data, $params + ['extra_twig_params' => $twig_params ?? []]);
    }
}
