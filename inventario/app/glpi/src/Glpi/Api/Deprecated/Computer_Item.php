<?php

namespace Glpi\Api\Deprecated;

use Glpi\Asset\Asset_PeripheralAsset;

class Computer_Item implements DeprecatedInterface
{
    use CommonDeprecatedTrait;

    public function getType(): string
    {
        return Asset_PeripheralAsset::class;
    }

    public function mapCurrentToDeprecatedHateoas(array $hateoas): array
    {
        $hateoas = $this->replaceCurrentHateoasRefByDeprecated($hateoas);
        return $hateoas;
    }

    public function mapDeprecatedToCurrentFields(object $fields): object
    {
        $this
         ->renameField($fields, 'computers_id', 'items_id_asset')
         ->addField($fields, 'itemtype_asset', 'Computer')
         ->renameField($fields, 'items_id', 'items_id_peripheral')
         ->renameField($fields, 'itemtype', 'itemtype_peripheral');

        return $fields;
    }

    public function mapCurrentToDeprecatedFields(array $fields): array
    {
        $this
         ->renameField($fields, 'items_id_asset', 'computers_id')
         ->deleteField($fields, 'itemtype_asset')
         ->renameField($fields, 'items_id_peripheral', 'items_id')
         ->renameField($fields, 'itemtype_peripheral', 'itemtype');

        return $fields;
    }

    public function mapDeprecatedToCurrentCriteria(array $criteria): array
    {
        return $criteria;
    }

    public function mapCurrentToDeprecatedSearchOptions(array $soptions): array
    {
        $this
         ->updateSearchOptionsUids($soptions)
         ->updateSearchOptionsTables($soptions);

        return $soptions;
    }
}
