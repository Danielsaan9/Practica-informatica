<?php

/**
 * ITILTemplateHiddenField Class
 *
 * Predefined fields for ITIL template class
 *
 * @since 9.5.0
 **/
abstract class ITILTemplateHiddenField extends ITILTemplateField
{
    public static function getTypeName($nb = 0)
    {
        return _n('Hidden field', 'Hidden fields', $nb);
    }

    public static function getIcon(): string
    {
        return 'ti ti-eye-off';
    }

    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {

        // can exists for template
        if (
            $item instanceof ITILTemplate
            && Session::haveRight(ITILTemplate::$rightname, READ)
        ) {
            $nb = 0;
            if ($_SESSION['glpishow_count_on_tabs']) {
                $nb = countElementsInTable(
                    $this->getTable(),
                    [static::$items_id => $item->getID()]
                );
            }
            return self::createTabEntry(self::getTypeName(Session::getPluralNumber()), $nb, $item::class);
        }
        return '';
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        if (!$item instanceof ITILTemplate) {
            return false;
        }

        self::showForITILTemplate($item, $withtemplate, false);
        return true;
    }


    public function post_purgeItem()
    {
        global $DB;

        parent::post_purgeItem();

        $itil_object = getItemForItemtype(static::$itiltype);
        $itemtype_id = $itil_object->getSearchOptionIDByField('field', 'itemtype', $itil_object->getTable());
        $items_id_id = $itil_object->getSearchOptionIDByField('field', 'items_id', $itil_object->getTable());

        // Try to delete itemtype -> delete items_id
        if ($this->fields['num'] == $itemtype_id) {
            $iterator = $DB->request([
                'SELECT' => 'id',
                'FROM'   => $this->getTable(),
                'WHERE'  => [
                    static::$items_id => $this->fields[static::$itiltype],
                    'num'             => $items_id_id,
                ],
            ]);
            if (count($iterator)) {
                $result = $iterator->current();
                $a = new static();
                $a->delete(['id' => $result['id']]);
            }
        }
    }


    /**
     * Get hidden fields for a template
     *
     * @param int  $ID                  the template ID
     * @param bool $withtypeandcategory with type and category (false by default)
     *
     * @return array of hidden fields
     */
    public function getHiddenFields($ID, $withtypeandcategory = false)
    {
        global $DB;

        $iterator = $DB->request([
            'FROM'   => $this->getTable(),
            'WHERE'  => [static::$items_id => $ID],
            'ORDER'  => 'id',
        ]);

        $tt             = getItemForItemtype(static::$itemtype);
        $allowed_fields = $tt->getAllowedFields($withtypeandcategory);
        $fields         = [];

        foreach ($iterator as $rule) {
            if (isset($allowed_fields[$rule['num']])) {
                $fields[$allowed_fields[$rule['num']]] = $rule['num'];
            }
        }
        return $fields;
    }


    /**
     * Return fields who doesn't need to be used for this part of template
     *
     * @since 9.2
     *
     * @return array the excluded fields (keys and values are equals)
     */
    public static function getExcludedFields()
    {
        return [
            175 => 175, // ticket's tasks (template)
        ];
    }
}
