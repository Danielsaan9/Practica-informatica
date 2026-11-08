<?php

use Glpi\Toolbox\ArrayNormalizer;

/**
 * Aggregate instantiation of NetworkPort. Aggregate can represent a trunk on switch, specific port under that regroup several ethernet ports to manage Ethernet Bridging.
 * @since 0.84
 */
class NetworkPortAggregate extends NetworkPortInstantiation
{
    public static function getTypeName($nb = 0)
    {
        return __('Aggregation port');
    }

    public function prepareInputForAdd($input)
    {
        if ((isset($input['networkports_id_list']))) {
            $input['networkports_id_list'] = exportArrayToDB(
                ArrayNormalizer::normalizeValues($input['networkports_id_list'] ?: [], 'intval')
            );
        }
        return parent::prepareInputForAdd($input);
    }

    public function prepareInputForUpdate($input)
    {
        if ((isset($input['networkports_id_list']))) {
            $input['networkports_id_list'] = exportArrayToDB(
                ArrayNormalizer::normalizeValues($input['networkports_id_list'] ?: [], 'intval')
            );
        }
        return parent::prepareInputForAdd($input);
    }

    public function showInstantiationForm(NetworkPort $netport, $options, $recursiveItems)
    {
        if (
            isset($this->fields['networkports_id_list'])
            && is_string($this->fields['networkports_id_list'])
        ) {
            $this->fields['networkports_id_list']
                        = importArrayFromDB($this->fields['networkports_id_list']);
        }

        $this->showMacField($netport, $options);
        $this->showNetworkPortSelector($recursiveItems, static::class);
    }
}
