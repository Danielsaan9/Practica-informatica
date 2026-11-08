<?php

use Glpi\Application\View\TemplateRenderer;

use function Safe\preg_match;

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if (isset($_POST['value']) && (strcmp($_POST['value'], '0') == 0)) {
    global $CFG_GLPI;

    $name           = $_POST['name'];
    $specific_value = $_POST['specificvalue'];
    $rand           = mt_rand();

    $date_part   = date('Y-m-d');
    $hour_part   = '00';
    $minute_part = '00';
    $has_time    = false;

    if (preg_match('/^(\d{4}-\d{2}-\d{2}) (\d{2}):(\d{2})/', $specific_value, $m)) {
        [$date_part, $hour_part, $minute_part] = [$m[1], $m[2], $m[3]];
        $has_time = true;
    } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $specific_value)) {
        $date_part = $specific_value;
    }

    TemplateRenderer::getInstance()->display('components/form/genericdate_picker.html.twig', [
        'name'          => $name,
        'rand'          => $rand,
        'has_time'      => $has_time,
        'initial_value' => $has_time ? "{$date_part} {$hour_part}:{$minute_part}:00" : $date_part,
        'timestep'      => max(1, (int) ($CFG_GLPI['time_step'] ?? 5)),
    ]);
} else {
    echo "<input type='hidden' name='" . htmlescape($_POST['name']) . "' value='" . htmlescape($_POST['value']) . "'>";
}
