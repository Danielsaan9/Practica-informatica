<?php

/// Class DocumentCategory
class DocumentCategory extends CommonTreeDropdown
{
    public bool $can_be_translated = true;


    public static function getTypeName($nb = 0)
    {
        return _n('Document heading', 'Document headings', $nb);
    }


    public function cleanRelationData()
    {

        parent::cleanRelationData();

        if ($this->isUsedAsDefaultCategoryForTickets()) {
            $newval = ($this->input['_replace_by'] ?? 0);

            Config::setConfigurationValues(
                'core',
                [
                    'documentcategories_id_forticket' => $newval,
                ]
            );
        }
    }


    public function isUsed()
    {

        if (parent::isUsed()) {
            return true;
        }

        return $this->isUsedAsDefaultCategoryForTickets();
    }


    /**
     * Check if category is used as default for tickets documents.
     *
     * @return bool
     */
    private function isUsedAsDefaultCategoryForTickets()
    {

        $config_values = Config::getConfigurationValues('core', ['documentcategories_id_forticket']);

        return array_key_exists('documentcategories_id_forticket', $config_values)
         && $config_values['documentcategories_id_forticket'] == $this->fields['id'];
    }

    public static function getIcon()
    {
        return "ti ti-tags";
    }
}
