<?php

namespace Glpi\Inventory\MainAsset;

use Blacklist;
use Glpi\Inventory\Asset\NetworkCard;
use RuleDefineItemtypeCollection;
use stdClass;

class Itemtype extends MainAsset
{
    protected array $extra_data = [
        'hardware' => null,
        'bios' => null,
        'users' => null,
        NetworkCard::class => null,
        'network_device' => null,
        'network_components' => null,
    ];

    /**
     * @param stdClass $data
     */
    public function __construct($data)
    {
        $namespaced = explode('\\', static::class);
        if ($item = getItemForItemtype(array_pop($namespaced))) {
            $this->itemtype = $item::class;
        }
        //store raw data for reference
        $this->raw_data = $data;
    }

    protected function getModelsFieldName(): string
    {
        return '';
    }

    protected function getTypesFieldName(): string
    {
        return '';
    }

    /**
     * @param string $original_itemtype
     *
     * @return array<string, mixed>
     */
    public function defineItemtype($original_itemtype): array
    {
        $blacklist = new Blacklist();

        $data = $this->data[0] ?? null; //there is only one data entry for MainAsset
        if (!$data) {
            return [];
        }

        //netrwok equipments information are store in extra node network_device
        if (isset($this->extra_data['network_device'])) {
            $data = (object) array_merge((array) $data, (array) $this->extra_data['network_device']);
        }

        $blacklist->processBlackList($data);
        $input = $this->prepareAllRulesInput($data);

        //Force correct itemtype for rules
        $input['itemtype'] = $original_itemtype;
        $itemtype_rule = new RuleDefineItemtypeCollection();
        $itemtype_rule->getCollectionPart();
        $data_itemtype = $itemtype_rule->processAllRules($input);
        return $data_itemtype;
    }
}
