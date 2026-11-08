<?php

/**
 * Alias instantiation of NetworkPort. An alias can be use to define VLAN tagged ports.
 * It is used in old versiond of Linux to define several IP addresses to a given port.
 * @since 0.84
 */
class NetworkPortAlias extends NetworkPortInstantiation
{
    public static function getTypeName($nb = 0)
    {
        return __('Alias port');
    }

    /**
     * @param array $input
     *
     * @return array
     */
    public function prepareInput($input)
    {
        // Try to get mac address from the instantiation ...
        if (
            !isset($input['mac'])
            && isset($input['networkports_id_alias'])
        ) {
            $networkPort = new NetworkPort();
            if ($networkPort->getFromDB($input['networkports_id_alias'])) {
                $input['mac']            = $networkPort->fields['mac'];
            }
        }

        return $input;
    }

    public function prepareInputForAdd($input)
    {
        return parent::prepareInputForAdd($this->prepareInput($input));
    }

    public function prepareInputForUpdate($input)
    {
        return parent::prepareInputForUpdate($this->prepareInput($input));
    }

    public function showInstantiationForm(NetworkPort $netport, $options, $recursiveItems)
    {
        $this->showMacField($netport, $options);
        $this->showNetworkPortSelector($recursiveItems, static::class);
    }
}
