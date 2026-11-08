<?php

namespace Glpi\Api\Deprecated;

/**
 * @since 9.4.0
 */
class TicketFollowup implements DeprecatedInterface
{
    use CommonDeprecatedTrait;

    public function getType(): string
    {
        return "ITILFollowup";
    }

    public function mapCurrentToDeprecatedHateoas(array $hateoas): array
    {
        $hateoas = $this->replaceCurrentHateoasRefByDeprecated($hateoas);
        return $hateoas;
    }

    public function mapDeprecatedToCurrentFields(object $fields): object
    {
        $this
         ->renameField($fields, "tickets_id", "items_id")
         ->addField($fields, "itemtype", "Ticket");

        return $fields;
    }

    public function mapCurrentToDeprecatedFields(array $fields): array
    {
        $this
         ->renameField($fields, "items_id", "tickets_id")
         ->deleteField($fields, "itemtype")
         ->deleteField($fields, "sourceitems_id")
         ->deleteField($fields, "sourceof_items_id");

        return $fields;
    }

    public function mapDeprecatedToCurrentCriteria(array $criteria): array
    {
        // Add itemtype condition
        $criteria[] = [
            "link"       => 'AND',
            "field"      => "6",
            "searchtype" => 'equals',
            "value"      => "Ticket",
        ];

        return $criteria;
    }

    public function mapCurrentToDeprecatedSearchOptions(array $soptions): array
    {
        $this
         ->updateSearchOptionsUids($soptions)
         ->updateSearchOptionsTables($soptions)
         ->alterSearchOption($soptions, "1", [
             "available_searchtypes" => ["contains"],
         ])
         ->alterSearchOption($soptions, "2", [
             "available_searchtypes" => [
                 "contains",
                 "equals",
                 "notequals",
             ],
         ])
         ->alterSearchOption($soptions, "3", [
             "available_searchtypes" => [
                 "equals",
                 "notequals",
                 "lessthan",
                 "morethan",
                 "contains",
             ],
         ])
         ->alterSearchOption($soptions, "4", [
             "available_searchtypes" => [
                 "equals",
                 "notequals",
                 "contains",
             ],
         ])
         ->alterSearchOption($soptions, "5", [
             "available_searchtypes" => [
                 "contains",
                 "equals",
                 "notequals",
             ],
         ])
         ->deleteSearchOption($soptions, "6")
         ->deleteSearchOption($soptions, "119")
         ->deleteSearchOption($soptions, "document");

        return $soptions;
    }
}
