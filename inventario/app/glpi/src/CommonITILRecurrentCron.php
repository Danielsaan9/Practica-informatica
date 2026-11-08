<?php

use Glpi\DBAL\QueryFunction;

/**
 * Cron task creating recurrent tickets and changes
 *
 * @since 10.0.0
 */
class CommonITILRecurrentCron extends CommonDBTM
{
    /**
     * Get cron task's description
     *
     * @return array
     */
    public static function cronInfo(): array
    {
        return [
            'description' => __("Create recurrent tickets and changes"),
        ];
    }

    /**
     * Run the cron task
     *
     * @param CronTask $task
     *
     * @return int task status (0: no work to do, 1: work done)
     */
    public static function cronRecurrentItems(CronTask $task)
    {
        global $DB;

        $total = 0;

        /**
         * Concrete classes for which recurrent items can be created
         * @var array<class-string<CommonITILRecurrent>>
         */
        $targets = [
            TicketRecurrent::class,
            RecurrentChange::class,
        ];

        foreach ($targets as $itemtype) {
            $iterator = $DB->request([
                'FROM'   => $itemtype::getTable(),
                'WHERE'  => [
                    'next_creation_date' => ['<', QueryFunction::now()],
                    'is_active'          => 1,
                    'OR'                 => [
                        ['end_date' => null],
                        ['end_date' => ['>', QueryFunction::now()]],
                    ],
                ],
            ]);

            foreach ($iterator as $data) {
                $item = getItemForItemtype($itemtype);
                $item->fields = $data;
                // get items
                $related_items = $item->getRelatedElements();

                if ($item->fields['ticket_per_item'] ?? false) {
                    // createItem() moves the next creation date, all tickets must use the current one
                    $next_creation_date = $item->fields['next_creation_date'];
                    foreach ($related_items as $related_itemtype => $related_items_ids) {
                        foreach ($related_items_ids as $related_item_id) {
                            $item->fields['next_creation_date'] = $next_creation_date;
                            if ($item->createItem([$related_itemtype => [$related_item_id]])) {
                                $total++;
                            } else {
                                //TRANS: %s is a name
                                $task->log(
                                    sprintf(
                                        __('Failed to create recurrent item %s'),
                                        $data['name']
                                    )
                                );
                            }
                        }
                    }
                } else {
                    if ($item->createItem($related_items)) {
                        $total++;
                    } else {
                        //TRANS: %s is a name
                        $task->log(
                            sprintf(
                                __('Failed to create recurrent item %s'),
                                $data['name']
                            )
                        );
                    }
                }
            }
        }

        $task->setVolume($total);
        return (int) ($total > 0);
    }
}
