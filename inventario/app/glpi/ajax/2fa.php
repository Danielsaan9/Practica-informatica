<?php

use Glpi\Security\TOTPManager;

use function Safe\json_encode;

if (isset($_POST['regenerate_backup_codes'])) {
    $totp = new TOTPManager();
    $codes = $totp->regenerateBackupCodes(Session::getLoginUserID());
    echo json_encode($codes);
}
