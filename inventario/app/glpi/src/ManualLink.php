<?php

use Glpi\Application\View\TemplateRenderer;
use Glpi\DBAL\QueryIdentifier;

/**
 * @since 10.0.0
 */
class ManualLink extends CommonDBChild
{
    public bool $dohistory              = false;
    public bool $auto_message_on_action = false; // Link in message can't work'
    protected bool $displaylist         = false;
    public static bool $logs_for_parent = true;
    public static string $itemtype        = 'itemtype';
    public static string $items_id        = 'items_id';

    public static function getTypeName($nb = 0)
    {
        return _n('Manual link', 'Manual links', $nb);
    }

    public function getLogTypeID()
    {
        return [$this->fields['itemtype'], $this->fields['items_id']];
    }

    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {

        $count = 0;
        if (
            $_SESSION['glpishow_count_on_tabs']
            && ($item instanceof CommonDBTM)
            && !$item->isNewItem()
        ) {
            $count += countElementsInTable(
                'glpi_manuallinks',
                [
                    'itemtype'  => $item::class,
                    'items_id'  => $item->fields[$item->getIndexName()],
                ]
            );
            if (Link::canView()) {
                $count += countElementsInTable(
                    ['glpi_links_itemtypes', 'glpi_links'],
                    [
                        'glpi_links_itemtypes.links_id'  => new QueryIdentifier('glpi_links.id'),
                        'glpi_links_itemtypes.itemtype'  => $item::class,
                    ] + getEntitiesRestrictCriteria('glpi_links', '', '', false)
                );
            }
        }
        return self::createTabEntry(_n('Link', 'Links', Session::getPluralNumber()), $count, $item::class);
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        if (!$item instanceof CommonDBTM) {
            return false;
        }

        Link::showAllLinksForItem($item);
        return true;
    }

    public function showForm($ID, array $options = [])
    {
        TemplateRenderer::getInstance()->display('pages/setup/manuallink.html.twig', [
            'item' => $this,
            'parent_item' => [
                'itemtype' => $options['itemtype'] ?? null,
                'items_id' => $options['items_id'] ?? null,
            ],
        ]);

        return true;
    }

    public function prepareInputForAdd($input)
    {
        if (!array_key_exists('url', $input) || empty($input['url'])) {
            Session::addMessageAfterRedirect(
                __s('URL is required'),
                false,
                ERROR
            );
            return false;
        }

        return $this->prepareInput($input);
    }

    public function prepareInputForUpdate($input)
    {
        return $this->prepareInput($input);
    }

    /**
     * Prepare input (for add/update).
     *
     * @return array|false
     */
    private function prepareInput(array $input)
    {
        if (array_key_exists('url', $input) && !empty($input['url']) && !Toolbox::isValidWebUrl($input['url'])) {
            Session::addMessageAfterRedirect(
                __s('Invalid URL'),
                false,
                ERROR
            );
            return false;
        }

        return $input;
    }

    /**
     * Return all manual links entries for given item.
     * @param CommonDBTM $item
     * @return array
     */
    public static function getForItem(CommonDBTM $item): iterable
    {
        global $DB;
        $iterator = $DB->request([
            'FROM'         => 'glpi_manuallinks',
            'WHERE'        => [
                'itemtype'  => $item::class,
                'items_id'  => $item->fields[$item->getIndexName()],
            ],
            'ORDERBY'      => 'name',
        ]);
        return $iterator;
    }

    /**
     * @param ?class-string<CommonDBTM> $itemtype
     *
     * @return array
     */
    public static function rawSearchOptionsToAdd($itemtype = null)
    {
        $tab = [];

        $tab[] = [
            'id'                 => '146',
            'table'              => static::getTable(),
            'field'              => '_virtual',
            'name'               => self::getTypeName(Session::getPluralNumber()),
            'datatype'           => 'specific',
            'additionalfields'   => [
                'id',
                'name',
                'url',
                'open_window',
                'icon',
            ],
            'forcegroupby'       => true,
            'nosearch'           => true,
            'nosort'             => true,
            'massiveaction'      => false,
            'joinparams'         => [
                'jointype'           => 'itemtype_item',
            ],
        ];

        return $tab;
    }

    public static function getSpecificValueToDisplay($field, $values, array $options = [])
    {

        if (!is_array($values)) {
            $values = [$field => $values];
        }
        switch ($field) {
            case '_virtual':
                return self::getLinkHtml($values);
        }
        return parent::getSpecificValueToDisplay($field, $values, $options);
    }

    /**
     * Returns link HTML code.
     *
     * @param array $fields
     *
     * @return string
     */
    public static function getLinkHtml(array $fields): string
    {

        if (empty($fields['url'])) {
            return '';
        }

        $html = '';

        $target = $fields['open_window'] == 1 ? '_blank' : '_self';
        $html .= '<a href="' . htmlescape($fields['url']) . '" target="' . $target . '">';
        if (str_starts_with($fields['icon'] ?? '', 'fa-')) {
            // Forces font family values to fallback on ".fab" family font if char is not available in ".fas" family.
            $html .= '<i class="fs-2 fa ' . htmlescape($fields['icon']) . '"'
            . ' style="font-family:\'Font Awesome 6 Free\', \'Font Awesome 6 Brands\';" aria-hidden="true"></i>&nbsp;';
        } elseif (str_starts_with($fields['icon'] ?? '', 'ti-')) {
            $html .= '<i class="fs-2 ti ' . htmlescape($fields['icon']) . '" aria-hidden="true"></i>&nbsp;';
        }
        $html .= htmlescape(!empty($fields['name']) ? $fields['name'] : $fields['url']);
        $html .= '</a>';

        return $html;
    }

    public static function getIcon()
    {
        return "ti ti-link";
    }
}
