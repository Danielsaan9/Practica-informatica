<?php

use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;

use function Safe\json_encode;

// Direct access to file

header("Content-Type: application/json; charset=UTF-8");

// Tech only
if (Session::getCurrentInterface() !== "central") {
    throw new AccessDeniedHttpException();
}

// Read parameter and load pending reason
$pending_reason = PendingReason::getById($_REQUEST['pendingreasons_id'] ?? null);
if (!$pending_reason) {
    throw new BadRequestHttpException();
}

echo json_encode([
    'followup_frequency'          => $pending_reason->fields['followup_frequency'],
    'followups_before_resolution' => $pending_reason->fields['followups_before_resolution'],
]);
