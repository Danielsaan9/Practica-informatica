<?php

namespace Glpi\Features;

use CommonDBTM;

/**
 * @phpstan-require-extends CommonDBTM
 */
interface AssignableItemInterface
{
    public static function canView(): bool;

    public function canViewItem(): bool;

    public static function canUpdate(): bool;

    public function canUpdateItem(): bool;

    public static function getAssignableVisiblityCriteria(): array;

    /**
     * @param string $interface
     * @phpstan-param 'central'|'helpdesk' $interface
     * @return array
     * @phpstan-return array<integer, string|array>
     */
    public function getRights($interface = 'central');

    /**
     * @param array $input
     *
     * @return false|array
     */
    public function prepareGroupFields(array $input);

    /**
     * @param array $input
     *
     * @return false|array
     */
    public function prepareInputForAdd($input);

    /**
     * @param array $input
     *
     * @return false|array
     */
    public function prepareInputForUpdate($input);

    /**
     * @return void
     */
    public function post_addItem();

    /**
     * @param bool $history
     *
     * @return void
     */
    public function post_updateItem($history = true);

    /**
     * @return bool
     */
    public function getEmpty();

    /**
     * @return void
     */
    public function post_getFromDB();

    /**
     * Update the values in the 'glpi_groups_items' link table as needed based on the groups set in the 'groups_id' and 'groups_id_tech' fields.
     *
     * @return void
     */
    public function updateGroupFields();
}
