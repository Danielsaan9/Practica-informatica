<?php

use Glpi\Application\View\TemplateRenderer;

class NetworkEquipmentModelStencil extends Stencil
{
    public static function getTypeName($nb = 0): string
    {
        return __('Graphical slot definition');
    }

    public function getPicturesFields(): array
    {
        return ['picture_front', 'picture_rear'];
    }

    public function getParams(bool $editor): array
    {
        if ($editor) {
            return [
                'nb_zones_label' => __('Set number of ports'),
                'define_zones_label' => __('Define port data in image'),
                'zone_label' => __('Port Label'),
                'zone_number_label' => __('Port Number'),
                'save_zone_data_label' => __('Save port data'),
                'add_zone_label' => __('Add a new port'),
                'remove_zone_label' => __('Remove last port'),
            ];
        } else {
            return [
                'anchor_id' => 'port_number_',
            ];
        }
    }

    public function getMaxZoneNumber(): int
    {
        return 256;
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        if (!$item instanceof CommonDBTM) {
            return false;
        }

        $stencil = Stencil::getStencilFromItem($item);
        if ($stencil != null) {
            $stencil->displayStencilEditor();
            return true;
        }

        return false;
    }

    public function getZoneLabel(bool $editor, array $zone): string
    {
        $zoneLabel = parent::getZoneLabel($editor, $zone);
        if (!$editor) {
            $portInformation = self::getPortInformation($zone);
            $statusHtml = TemplateRenderer::getInstance()->render('stencil/parts/port/status.html.twig', [
                'port' => $portInformation,
                'with_text' => false,
            ]);
            $zoneLabel .= $statusHtml;
        }
        return $zoneLabel;
    }

    public function getZonePopover(bool $editor, array $zone): string
    {
        $zonePopover = parent::getZonePopover($editor, $zone);
        if (!$editor) {
            $portInformation = self::getPortInformation($zone);
            $popoverHtml = TemplateRenderer::getInstance()->render('stencil/parts/port/popover.html.twig', [
                'port' => $portInformation,
            ]);
            $zonePopover .= $popoverHtml;
        }
        return $zonePopover;
    }

    private function getPortInformation(array $port): array
    {
        $networkPort = new NetworkPort();

        $port['ifstatus'] = -1;
        if (
            $networkPort->getFromDBByCrit([
                'logical_number' => $port['number'],
                'items_id'  => $this->getStencilItem()->getID(),
                'itemtype'  => $this->getStencilItem()::class,
                'is_deleted' => 0,
            ]) && $networkPort->fields['ifstatus']
        ) {
            $port['ifstatus'] = $networkPort->fields['ifstatus'];
        }

        return $port;
    }
}
