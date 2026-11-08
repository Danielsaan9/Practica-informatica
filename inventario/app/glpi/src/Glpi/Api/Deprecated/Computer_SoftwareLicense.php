<?php

namespace Glpi\Api\Deprecated;

/**
 * @since 9.5
 */
class Computer_SoftwareLicense implements DeprecatedInterface
{
    use CommonDeprecatedTrait;

    public function getType(): string
    {
        return "Item_SoftwareLicense";
    }

    public function mapCurrentToDeprecatedHateoas(array $hateoas): array
    {
        $hateoas = $this->replaceCurrentHateoasRefByDeprecated($hateoas);
        return $hateoas;
    }

    public function mapDeprecatedToCurrentFields(object $fields): object
    {
        $this
         ->renameField($fields, "computers_id", "items_id")
         ->addField($fields, "itemtype", "Computer");

        return $fields;
    }

    public function mapCurrentToDeprecatedFields(array $fields): array
    {
        $this
         ->renameField($fields, "items_id", "computers_id")
         ->deleteField($fields, "itemtype");

        return $fields;
    }

    public function mapDeprecatedToCurrentCriteria(array $criteria): array
    {
        $criteria[] = [
            "link"       => 'AND',
            "field"      => "6",
            "searchtype" => 'equals',
            "value"      => "Computer",
        ];

        return $criteria;
    }

    public function mapCurrentToDeprecatedSearchOptions(array $soptions): array
    {
        $this
         ->updateSearchOptionsUids($soptions)
         ->updateSearchOptionsTables($soptions)
         ->alterSearchOption($soptions, "5", [
             'name'                  => "Computer",
             'table'                 => "glpi_computers",
             'field'                 => "name",
             'datatype'              => "dropdown",
             'uid'                   => "Computer_SoftwareLicense.Computer.name",
             'available_searchtypes' => [
                 "contains",
                 "notcontains",
                 "equals",
                 "notequals",
             ],
         ])
         ->deleteSearchOption($soptions, "6");

        return $soptions;
    }
}
