<?php

use Glpi\Application\View\TemplateRenderer;

class Item_DeviceCamera_ImageFormat extends CommonDBRelation
{
    public static ?string $itemtype_1 = Item_DeviceCamera::class;
    public static ?string $items_id_1 = 'items_devicecameras_id';

    public static ?string $itemtype_2 = ImageFormat::class;
    public static ?string $items_id_2 = 'imageformats_id';

    public static function getTypeName($nb = 0)
    {
        return _nx('camera', 'Format', 'Formats', $nb);
    }

    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        $nb = 0;
        if ($item instanceof CommonDBTM && $_SESSION['glpishow_count_on_tabs']) {
            $nb = countElementsInTable(
                self::getTable(),
                [
                    'items_devicecameras_id' => $item->getID(),
                ]
            );
        }
        return self::createTabEntry(self::getTypeName(Session::getPluralNumber()), $nb, $item::class);
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        if (!$item instanceof DeviceCamera) {
            return false;
        }
        return self::showItems($item);
    }

    public function getForbiddenStandardMassiveAction()
    {
        $forbidden   = parent::getForbiddenStandardMassiveAction();
        $forbidden[] = 'MassiveAction:update';
        $forbidden[] = 'CommonDBConnexity:affect';
        $forbidden[] = 'CommonDBConnexity:unaffect';

        return $forbidden;
    }

    /**
     * Print items
     * @param  DeviceCamera $camera the current camera instance
     * @return bool
     */
    public static function showItems(DeviceCamera $camera): bool
    {
        global $DB;

        $ID = $camera->getID();
        $rand = mt_rand();

        if (
            !$camera->getFromDB($ID)
            || !$camera->can($ID, READ)
        ) {
            return false;
        }
        $canedit = $camera->canEdit($ID);

        $items = $DB->request([
            'SELECT' => ['id', 'imageformats_id', 'is_dynamic'],
            'FROM'   => self::getTable(),
            'WHERE'  => [
                'items_devicecameras_id' => $camera->getID(),
            ],
        ]);

        $entries = [];
        foreach ($items as $row) {
            $item = new ImageFormat();
            $item->getFromDB($row['imageformats_id']);
            $entries[] = [
                'itemtype' => self::class,
                'id' => $row['id'],
                'imageformats_id' => $item->getLink(),
                'is_dynamic' => $row['is_dynamic'],
            ];
        }

        TemplateRenderer::getInstance()->display('components/datatable.html.twig', [
            'is_tab' => true,
            'nofilter' => true,
            'columns' => [
                'imageformats_id' => ImageFormat::getTypeName(1),
                'is_dynamic' => __('Is dynamic'),
            ],
            'formatters' => [
                'imageformats_id' => 'raw_html',
            ],
            'entries' => $entries,
            'total_number' => count($entries),
            'showmassiveactions' => $canedit,
            'massiveactionparams' => [
                'num_displayed' => min($_SESSION['glpilist_limit'], count($entries)),
                'container'     => 'mass' . static::class . $rand,
            ],
        ]);

        return true;
    }

    public static function getIcon()
    {
        return "ti ti-photo-cog";
    }
}
