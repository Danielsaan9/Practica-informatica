<?php

/// Class SoftwareCategory
class SoftwareCategory extends CommonTreeDropdown
{
    public bool $can_be_translated = true;


    public static function getTypeName($nb = 0)
    {
        return _n('Software category', 'Software categories', $nb);
    }


    public function cleanDBonPurge()
    {
        Rule::cleanForItemAction($this);
    }


    public function cleanRelationData()
    {

        parent::cleanRelationData();

        if ($this->isUsedAsCategoryOnSoftwareDeletion()) {
            $newval = ($this->input['_replace_by'] ?? 0);

            Config::setConfigurationValues(
                'core',
                [
                    'softwarecategories_id_ondelete' => $newval,
                ]
            );
        }
    }


    public function isUsed()
    {

        if (parent::isUsed()) {
            return true;
        }

        return $this->isUsedAsCategoryOnSoftwareDeletion();
    }


    /**
     * Check if type is used as category for software deleted by rules.
     *
     * @return bool
     */
    private function isUsedAsCategoryOnSoftwareDeletion()
    {

        $config_values = Config::getConfigurationValues('core', ['softwarecategories_id_ondelete']);

        return array_key_exists('softwarecategories_id_ondelete', $config_values)
         && $config_values['softwarecategories_id_ondelete'] == $this->fields['id'];
    }

    public static function getIcon()
    {
        return Software::getIcon();
    }
}
