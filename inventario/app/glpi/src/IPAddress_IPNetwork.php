<?php

use Glpi\DBAL\QueryExpression;

/**
 * Class IPAddress_IPNetwork : Connection between IPAddress and IPNetwork
 *
 * @since 0.84
 **/
class IPAddress_IPNetwork extends CommonDBRelation
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = IPAddress::class;
    public static ?string $items_id_1 = 'ipaddresses_id';

    public static ?string $itemtype_2 = IPNetwork::class;
    public static ?string $items_id_2 = 'ipnetworks_id';


    /**
     * Update IPNetwork's dependency
     *
     * @param $network IPNetwork object
     *
     * @return void
     */
    public static function linkIPAddressFromIPNetwork(IPNetwork $network)
    {
        global $DB;

        $linkObject    = new self();
        $linkTable     = $linkObject->getTable();
        $ipnetworks_id = $network->getID();

        // First, remove all links of the current Network
        $iterator = $DB->request([
            'SELECT' => 'id',
            'FROM'   => $linkTable,
            'WHERE'  => ['ipnetworks_id' => $ipnetworks_id],
        ]);
        foreach ($iterator as $link) {
            $linkObject->delete(['id' => $link['id']]);
        }

        // Then, look each IP address contained inside current Network
        $iterator = $DB->request([
            'SELECT' => [
                new QueryExpression($DB->quoteValue($ipnetworks_id) . ' AS ' . $DB->quoteName('ipnetworks_id')),
                'id AS ipaddresses_id',
            ],
            'FROM'   => 'glpi_ipaddresses',
            'WHERE'  => $network->getCriteriaForMatchingElement('glpi_ipaddresses', 'binary', 'version'),
            'GROUP'  => 'id',
        ]);
        foreach ($iterator as $link) {
            $linkObject->add($link);
        }
    }


    /**
     * @param $ipaddress IPAddress object
     *
     * @return void
     */
    public static function addIPAddress(IPAddress $ipaddress)
    {

        $linkObject = new self();
        $input      = ['ipaddresses_id' => $ipaddress->getID()];

        $entity         = $ipaddress->getEntityID();
        $ipnetworks_ids = IPNetwork::searchNetworksContainingIP($ipaddress, $entity);
        if ($ipnetworks_ids !== false) {
            // Beware that invalid IPaddresses don't have any valid address !
            $entity = $ipaddress->getEntityID();
            foreach (IPNetwork::searchNetworksContainingIP($ipaddress, $entity) as $ipnetworks_id) {
                $input['ipnetworks_id'] = $ipnetworks_id;
                $linkObject->add($input);
            }
        }
    }
}
