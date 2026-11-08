<?php

/**
 * Class Supplier_Ticket
 *
 * @since 0.84
 **/
class Supplier_Ticket extends CommonITILActor
{
    // From CommonDBRelation
    public static ?string $itemtype_1 = Ticket::class;
    public static ?string $items_id_1 = 'tickets_id';
    public static ?string $itemtype_2 = Supplier::class;
    public static ?string $items_id_2 = 'suppliers_id';


    /**
     * @param int $items_id
     * @param string $email
     * @return bool
     *
     * @since 0.85
     **/
    public function isSupplierEmail($items_id, $email)
    {
        global $DB;

        $iterator = $DB->request([
            'FROM'      => $this->getTable(),
            'LEFT JOIN' => [
                'glpi_suppliers'  => [
                    'ON' => [
                        $this->getTable() => 'suppliers_id',
                        'glpi_suppliers'  => 'id',
                    ],
                ],
            ],
            'WHERE'     => [
                $this->getTable() . '.tickets_id'   => $items_id,
                'glpi_suppliers.email'              => $email,
            ],
        ]);

        foreach ($iterator as $data) {
            return true;
        }
        return false;
    }
}
