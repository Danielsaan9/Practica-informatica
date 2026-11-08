<?php

/**
 * Knowbase Class
 *
 * @since 0.84
 * @deprecated 12.0.0 The knowledge base is browsed from the side panel of `KnowbaseItem`.
 **/
class Knowbase extends CommonGLPI
{
    public static function getTypeName($nb = 0)
    {
        // No plural
        return __('Knowledge base');
    }

    public function defineTabs($options = [])
    {
        Toolbox::deprecated();

        $ong = [];
        $this->addStandardTab(self::class, $ong, $options);

        $ong['no_all_tab'] = true;
        return $ong;
    }

    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        Toolbox::deprecated();

        if ($item::class === self::class) {
            $tabs[1] = self::createTabEntry(_x('button', 'Search'), icon: 'ti ti-search');
            $tabs[2] = self::createTabEntry(_x('button', 'Browse'), icon: 'ti ti-list-tree');

            return $tabs;
        }
        return '';
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        Toolbox::deprecated();

        if ($item::class === self::class) {
            switch ($tabnum) {
                case 1: // all
                    $item->showSearchView();
                    break;

                case 2:
                    Search::show('KnowbaseItem');
                    break;
            }
        }
        return true;
    }

    /**
     * Show the knowbase search view
     *
     * @return void
     *
     * @deprecated 12.0.0
     */
    public static function showSearchView()
    {
        Toolbox::deprecated();

        global $CFG_GLPI;

        // Search a solution
        if (isset($_GET["itemtype"], $_GET["items_id"]) && !isset($_GET["contains"])) {
            if (in_array($_GET["item_itemtype"], $CFG_GLPI['kb_types'], true) && $item = getItemForItemtype($_GET["itemtype"])) {
                if ($item->can($_GET["item_items_id"], READ)) {
                    $_GET["contains"] = $item->fields['name'];
                }
            }
        }

        if (isset($_GET["contains"])) {
            $_SESSION['kbcontains'] = $_GET["contains"];
        } elseif (isset($_SESSION['kbcontains'])) {
            $_GET['contains'] = $_SESSION["kbcontains"];
        }
        $ki = new KnowbaseItem();
        $ki->searchForm($_GET);

        if (empty($_GET['contains'])) {
            echo '<div class="d-flex flex-wrap mt-3">';
            KnowbaseItem::showRecentPopular("recent");
            KnowbaseItem::showRecentPopular("lastupdate");
            KnowbaseItem::showRecentPopular("popular");
            echo '</div>';
        } else {
            KnowbaseItem::showList($_GET, 'search');
        }
    }
}
