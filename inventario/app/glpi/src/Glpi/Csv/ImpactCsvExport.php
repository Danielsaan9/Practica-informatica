<?php

namespace Glpi\Csv;

use CommonDBTM;
use Impact;
use ImpactContext;
use ImpactItem;

class ImpactCsvExport implements ExportToCsvInterface
{
    protected CommonDBTM $item;

    public function __construct(CommonDBTM $item)
    {
        $this->item = $item;
    }

    public function getFileName(): string
    {
        return "{$this->item->fields['name']}.csv";
    }

    public function getFileHeader(): array
    {
        return [
            __("Relation"),
            __("Itemtype"),
            __("Id"),
            __("Name"),
        ];
    }

    public function getFileContent(): array
    {
        $content = [];

        // Load graph and impactitem
        $graph = Impact::buildGraph($this->item);
        $impact_item = ImpactItem::findForItem($this->item);
        $impact_context = ImpactContext::findForImpactItem($impact_item);

        // Get depth param
        if (!$impact_context) {
            $max_depth = Impact::DEFAULT_DEPTH;
        } else {
            $max_depth = $impact_context->fields["max_depth"];
        }

        // Load list data
        $data = [];
        $directions = [Impact::DIRECTION_FORWARD, Impact::DIRECTION_BACKWARD];
        foreach ($directions as $direction) {
            $data[$direction] = Impact::buildListData(
                $graph,
                $direction,
                $this->item,
                $max_depth
            );
        }

        // Flatten the hiarchical $data and insert it line by line
        foreach ($data as $direction => $impact_data) {
            if ($direction == Impact::DIRECTION_FORWARD) {
                $direction_label = __("Impact");
            } else {
                $direction_label = __("Impacted by");
            }

            foreach ($impact_data as $data_type => $data_elements) {
                foreach ($data_elements as $data_element) {
                    $content[] = [
                        $direction_label,
                        $data_type,
                        $data_element['stored']->fields['id'],
                        $data_element['stored']->fields['name'],
                    ];
                }
            }
        }

        return $content;
    }
}
