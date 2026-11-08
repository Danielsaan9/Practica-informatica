<?php

use Glpi\Inventory\Request;

use function Safe\file_get_contents;

class RuleDefineItemtypeCollection extends RuleCollection
{
    // From RuleCollection
    public bool $stop_on_first_match = true;
    public static string $rightname           = 'rule_import';
    public string $menu_option         = 'defineasset';

    public function getTitle()
    {
        return __('Rules to define inventoried itemtype');
    }

    public function prepareInputDataForProcess($input, $params)
    {
        $refused_id = $params['refusedequipments_id'] ?? null;
        if ($refused_id === null) {
            return $input;
        }

        $refused = new RefusedEquipment();
        if ($refused->getFromDB($refused_id) && ($inventory_file = $refused->getInventoryFileName()) !== null) {
            $inventory_request = new Request();
            $contents = file_get_contents($inventory_file);
            $inventory_request
                ->testRules()
                ->handleRequest($contents);

            $inventory = $inventory_request->getInventory();
            $invitem = $inventory->getMainAsset();

            // sanitize input
            if ($input['itemtype'] == 0) {
                unset($input['itemtype']);
            }
            foreach ($input as $key => $value) {
                if (empty($value)) {
                    unset($input[$key]);
                }
            }

            $data = $invitem->getData();
            $rules_input = $invitem->prepareAllRulesInput($data[0]);

            // keep user values if any
            $input += $rules_input;
        } else {
            trigger_error(
                sprintf('Invalid RefusedEquipment "%s" or inventory file missing', $refused_id),
                E_USER_WARNING
            );
            $contents = '';
        }

        return $input;
    }
}
