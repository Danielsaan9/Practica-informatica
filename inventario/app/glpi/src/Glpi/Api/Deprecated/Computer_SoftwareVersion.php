<?php

namespace Glpi\Api\Deprecated;

/**
 * @since 9.5
 */
class Computer_SoftwareVersion implements DeprecatedInterface
{
    use CommonDeprecatedTrait;

    public function getType(): string
    {
        return "Item_SoftwareVersion";
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
         ->addField($fields, "itemtype", "Computer")
         ->renameField($fields, "is_template_computer", "is_template_item")
         ->renameField($fields, "is_deleted_computer", "is_deleted_item");

        return $fields;
    }

    public function mapCurrentToDeprecatedFields(array $fields): array
    {
        $this
         ->renameField($fields, "items_id", "computers_id")
         ->deleteField($fields, "itemtype")
         ->renameField($fields, "is_template_item", "is_template_computer")
         ->renameField($fields, "is_deleted_item", "is_deleted_computer");

        return $fields;
    }

    public function mapDeprecatedToCurrentCriteria(array $criteria): array
    {
        $criteria[] = [
            "link"       => 'AND',
            "field"      => "5",
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
         ->alterSearchOption($soptions, "3", [
             'name'                  => "Computer",
             'table'                 => "glpi_computers",
             'field'                 => "name",
             'datatype'              => "dropdown",
             'uid'                   => "Computer_SoftwareVersion.Computer.name",
             'available_searchtypes' => [
                 "contains",
                 "notcontains",
                 "equals",
                 "notequals",
             ],
         ])
         ->deleteSearchOption($soptions, "5");

        return $soptions;
    }
}
