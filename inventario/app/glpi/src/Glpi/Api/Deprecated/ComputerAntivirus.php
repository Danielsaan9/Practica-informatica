<?php

namespace Glpi\Api\Deprecated;

use Computer;

/**
 * @since 11.0.0
 */
class ComputerAntivirus implements DeprecatedInterface
{
    use CommonDeprecatedTrait;

    public function getType(): string
    {
        return 'ItemAntivirus';
    }

    public function mapCurrentToDeprecatedHateoas(array $hateoas): array
    {
        $hateoas = $this->replaceCurrentHateoasRefByDeprecated($hateoas);
        return $hateoas;
    }

    public function mapDeprecatedToCurrentFields(object $fields): object
    {
        $this->renameField($fields, 'computers_id', 'items_id');
        $this->addField($fields, 'itemtype', Computer::class);

        return $fields;
    }

    public function mapCurrentToDeprecatedFields(array $fields): array
    {
        $this->renameField($fields, 'items_id', 'computers_id');
        $this->deleteField($fields, 'itemtype');

        return $fields;
    }

    public function mapDeprecatedToCurrentCriteria(array $criteria): array
    {
        // Add itemtype condition
        $criteria[] = [
            'link'       => 'AND',
            'field'      => '4',
            'searchtype' => 'equals',
            'value'      => Computer::class,
        ];

        return $criteria;
    }

    public function mapCurrentToDeprecatedSearchOptions(array $soptions): array
    {
        $this->updateSearchOptionsUids($soptions);
        $this->deleteSearchOption($soptions, '4');

        $soptions = array_map(
            function ($soption) {
                if (isset($soption['table']) && $soption['table'] === 'glpi_itemantiviruses') {
                    $soption['table'] = 'glpi_computerantiviruses';
                }
                return $soption;
            },
            $soptions
        );

        return $soptions;
    }
}
