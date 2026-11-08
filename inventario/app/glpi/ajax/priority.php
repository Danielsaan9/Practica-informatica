<?php

use function Safe\json_encode;

Html::header_nocache();

if (
    isset($_REQUEST["urgency"])
    && isset($_REQUEST["impact"])
) {
    // Read predefined templates fields
    $predefined_fields  = array_key_exists('_predefined_fields', $_REQUEST) ? Toolbox::decodeArrayFromInput($_REQUEST["_predefined_fields"]) : [];

    // Fallback to Form value -> Template values -> Medium
    $priority = Ticket::computePriority(
        (int) ($_REQUEST["urgency"] ?: $predefined_fields['urgency'] ?? 3) /* Medium */,
        (int) ($_REQUEST["impact"] ?: $predefined_fields['impact'] ?? 3) /* Medium */
    );

    if (isset($_REQUEST['getJson'])) {
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(['priority' => $priority]);
    } elseif ($_REQUEST["priority"]) {
        // Send UTF8 Headers
        header("Content-Type: text/html; charset=UTF-8");
        echo Html::scriptBlock(
            sprintf(
                '$("#%s").trigger("setValue", "%s");',
                jsescape($_REQUEST["priority"]),
                jsescape($priority)
            )
        );
    } else {
        echo htmlescape(Ticket::getPriorityName($priority));
    }
}
