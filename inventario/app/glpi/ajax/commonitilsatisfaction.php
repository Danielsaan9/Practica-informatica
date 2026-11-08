<?php

use Glpi\Application\View\TemplateRenderer;
use Glpi\Exception\Http\AccessDeniedHttpException;

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

$itemtype = $_POST['itemtype'];
$ent = new Entity();
// Get suffix for entity config fields. For backwards compatibility, ticket values have no suffix.
$config_suffix = $itemtype::getType() === 'Ticket' ? '' : ('_' . strtolower($itemtype::getType()));

if (isset($_POST['inquest_config' . $config_suffix], $_POST['entities_id'])) {
    if ($ent->getFromDB($_POST['entities_id'])) {
        if (!$ent->can($ent->getID(), READ)) {
            throw new AccessDeniedHttpException();
        }
        $inquest_delay             = $ent->fields['inquest_delay' . $config_suffix];
        $inquest_rate              = $ent->fields['inquest_rate' . $config_suffix];
        $inquest_duration          = $ent->fields['inquest_duration' . $config_suffix];
        $inquest_max_rate          = $ent->fields['inquest_max_rate' . $config_suffix];
        $inquest_default_rate      = $ent->fields['inquest_default_rate' . $config_suffix];
        $inquest_mandatory_comment = $ent->fields['inquest_mandatory_comment' . $config_suffix];
        $max_closedate             = $ent->fields['max_closedate' . $config_suffix];
    } else {
        $inquest_delay             = -1;
        $inquest_rate              = -1;
        $inquest_duration          = 0;
        $inquest_default_rate      = 3;
        $inquest_max_rate          = 5;
        $inquest_mandatory_comment = 0;
        $max_closedate             = '';
    }

    if ((int) $_POST['inquest_config' . $config_suffix] > 0) {
        TemplateRenderer::getInstance()->display('pages/admin/entity/survey_config.html.twig', [
            'itemtype' => $itemtype,
            'inquest_config' => $_POST['inquest_config' . $config_suffix],
            'config_suffix' => $config_suffix,
            'inquest_delay' => $inquest_delay,
            'inquest_rate' => $inquest_rate,
            'inquest_duration' => $inquest_duration,
            'inquest_default_rate' => $inquest_default_rate,
            'inquest_max_rate' => $inquest_max_rate,
            'inquest_mandatory_comment' => $inquest_mandatory_comment,
            'max_closedate' => $max_closedate,
            'inquest_URL' => $ent->fields['inquest_URL' . $config_suffix] ?? '',
        ]);
    }
}
