<?php

use Com\Tecnick\Barcode\Barcode;
use Com\Tecnick\Barcode\Model;
use Glpi\DBAL\QueryExpression;
use Glpi\DBAL\QueryFunction;
use Glpi\DBAL\QueryIdentifier;

class BarcodeManager
{
    /**
     * @param CommonDBTM $item
     *
     * @return Model|false
     */
    public function generateQRCode(CommonDBTM $item)
    {
        global $CFG_GLPI;
        if (
            $item->isNewItem()
            || !in_array($item::class, $CFG_GLPI["asset_types"])
        ) {
            return false;
        }
        $barcode = new Barcode();
        $qrcode = $barcode->getBarcodeObj(
            'QRCODE,H',
            $CFG_GLPI["url_base"] . $item::getFormURLWithID($item->getID(), false),
            200,
            200,
            'black',
            [10, 10, 10, 10]
        )->setBackgroundColor('white');
        return $qrcode;
    }

    /**
     * @param string $itemtype
     *
     * @return array<mixed, array<string, mixed>>
     */
    public static function rawSearchOptionsToAdd(string $itemtype): array
    {
        global $CFG_GLPI, $DB;

        if (!in_array($itemtype, $CFG_GLPI["asset_types"])) {
            return [];
        }

        $url_prefix = $CFG_GLPI['url_base'] . $itemtype::getFormURL(false) . '?id=';

        return [
            [
                'id'            => 290,
                'table'         => $itemtype::getTable(),
                'field'         => 'asset_url',
                'name'          => __('Asset URL'),
                'massiveaction' => false,
                'nometa'        => true,
                'nosort'        => true,
                'datatype'      => 'string',
                'computation'   => QueryFunction::concat([
                    new QueryExpression('?', null, [$url_prefix]),
                    new QueryIdentifier('TABLE.id'),
                ]),
            ],
        ];
    }

    /**
     * @param CommonDBTM $item
     *
     * @return false|string
     */
    public static function renderQRCode(CommonDBTM $item)
    {
        $barcode_manager = new self();
        $qrcode = $barcode_manager->generateQRCode($item);
        if ($qrcode) {
            return "<img src=\"data:image/png;base64," . base64_encode($qrcode->getPngData()) . "\" />";
        }
        return false;
    }
}
