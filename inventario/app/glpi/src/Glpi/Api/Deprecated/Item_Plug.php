<?php

namespace Glpi\Api\Deprecated;

use Plug;

class Item_Plug implements DeprecatedInterface
{
    use CommonDeprecatedTrait;

    public function getType(): string
    {
        return Plug::class;
    }

    public function mapCurrentToDeprecatedHateoas(array $hateoas): array
    {
        return $this->replaceCurrentHateoasRefByDeprecated($hateoas);
    }

    public function mapDeprecatedToCurrentFields(object $fields): object
    {
        $this->renameField($fields, 'items_id', 'items_id_main');
        $this->renameField($fields, 'itemtype', 'itemtype_main');

        return $fields;
    }

    public function mapCurrentToDeprecatedFields(array $fields): array
    {
        $this->renameField($fields, 'items_id_main', 'items_id');
        $this->renameField($fields, 'itemtype_main', 'itemtype');

        return $fields;
    }

    public function mapDeprecatedToCurrentCriteria(array $criteria): array
    {
        return $criteria;
    }

    public function mapCurrentToDeprecatedSearchOptions(array $soptions): array
    {
        $this->updateSearchOptionsUids($soptions);

        return array_map(
            static function ($soption) {
                if (isset($soption['table']) && $soption['table'] === 'glpi_pdu_plugs') {
                    $soption['table'] = 'glpi_plugs';
                }
                return $soption;
            },
            $soptions
        );
    }
}
