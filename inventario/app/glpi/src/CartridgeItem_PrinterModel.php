<?php

/**
 *  Relation between CartridgeItem and PrinterModel
 *  @since 0.84
 **/
class CartridgeItem_PrinterModel extends CommonDBRelation
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = CartridgeItem::class;
    public static ?string $items_id_1          = 'cartridgeitems_id';

    public static ?string $itemtype_2 = PrinterModel::class;
    public static ?string $items_id_2          = 'printermodels_id';
    public static int $checkItem_2_Rights  = self::DONT_CHECK_ITEM_RIGHTS;



    public function getForbiddenStandardMassiveAction()
    {

        $forbidden   = parent::getForbiddenStandardMassiveAction();
        $forbidden[] = 'update';
        return $forbidden;
    }


    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        switch (true) {
            case $item instanceof CartridgeItem:
                self::showForCartridgeItem($item);
                break;
        }
        return true;
    }


    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        if (!$item instanceof CommonDBTM) {
            return '';
        }

        if (!$withtemplate && Printer::canView()) {
            $nb = 0;
            switch ($item::class) {
                case 'CartridgeItem':
                    if ($_SESSION['glpishow_count_on_tabs']) {
                        $nb = self::countForItem($item);
                    }
                    return self::createTabEntry(PrinterModel::getTypeName(Session::getPluralNumber()), $nb, $item::class);
            }
        }
        return '';
    }


    /**
     * Show the printer types that are compatible with a cartridge type
     *
     * @param $item   CartridgeItem object
     *
     * @return bool|void
     **/
    public static function showForCartridgeItem(CartridgeItem $item)
    {
        $instID = $item->getID();

        if (!$item->can($instID, READ)) {
            return false;
        }
        $canedit = $item->canEdit($instID);
        $rand    = mt_rand();

        $iterator = self::getListForItem($item);
        $number = count($iterator);

        $used  = [];
        $datas = [];
        foreach ($iterator as $data) {
            $used[$data["id"]] = $data["id"];
            $datas[$data["linkid"]]  = $data;
        }

        if ($canedit) {
            echo "<div class='firstbloc'>";
            echo "<form name='printermodel_form$rand' id='printermodel_form$rand' method='post'";
            echo " action='" . htmlescape(static::getFormURL()) . "'>";

            echo "<table class='tab_cadre_fixe'>";
            echo "<tr class='tab_bg_1'>";
            echo "<th colspan='6'>" . __s('Add a compatible printer model') . "</th></tr>";

            echo "<tr><td class='tab_bg_2 center'>";
            echo "<input type='hidden' name='cartridgeitems_id' value='$instID'>";
            PrinterModel::dropdown(['used' => $used]);
            echo "</td><td class='tab_bg_2 center'>";
            echo "<button type='submit' name='add' class='btn btn-primary'><i class='ti ti-link' aria-hidden='true'></i><span>" . _sx('button', 'Add') . "</span></button>";
            echo "</td></tr>";
            echo "</table>";
            Html::closeForm();
            echo "</div>";
        }

        if ($number) {
            echo "<div class='spaced'>";
            if ($canedit) {
                $rand     = mt_rand();
                Html::openMassiveActionsForm('mass' . self::class . $rand);
                $massiveactionparams = ['num_displayed' => min($_SESSION['glpilist_limit'], count($used)),
                    'container'     => 'mass' . self::class . $rand,
                ];
                Html::showMassiveActions($massiveactionparams);
            }

            echo "<table class='tab_cadre_fixehov'>";
            $header_begin  = "<tr>";
            $header_top    = '';
            $header_bottom = '';
            $header_end    = '';
            if ($canedit) {
                $header_begin  .= "<th width='10'>";
                $header_top    .= Html::getCheckAllAsCheckbox('mass' . self::class . $rand);
                $header_bottom .= Html::getCheckAllAsCheckbox('mass' . self::class . $rand);
                $header_end    .= "</th>";
            }
            $header_end .= "<th>" . _sn('Model', 'Models', 1) . "</th></tr>";
            echo $header_begin . $header_top . $header_end;

            foreach ($datas as $data) {
                echo "<tr class='tab_bg_1'>";
                if ($canedit) {
                    echo "<td width='10'>";
                    Html::showMassiveActionCheckBox(self::class, $data["linkid"]);
                    echo "</td>";
                }
                $opt = [
                    'is_deleted' => 0,
                    'criteria'   => [
                        [
                            'field'      => 40, // printer model
                            'searchtype' => 'equals',
                            'value'      => $data["id"],
                        ],
                    ],
                ];
                $url = Printer::getSearchURL() . "?" . Toolbox::append_params($opt);
                echo "<td class='center'><a href='" . htmlescape($url) . "'>" . htmlescape($data["name"]) . "</a></td>";
                echo "</tr>";
            }
            echo $header_begin . $header_bottom . $header_end;
            echo "</table>";
            if ($canedit) {
                $massiveactionparams['ontop'] = false;
                Html::showMassiveActions($massiveactionparams);
                Html::closeForm();
            }
            echo "</div>";
        } else {
            echo "<p class='center b'>" . __s('No results found') . "</p>";
        }
    }
}
