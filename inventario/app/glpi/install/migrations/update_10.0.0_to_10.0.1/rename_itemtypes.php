<?php

/**
 * @var Migration $migration
 */
$mapping = [
    'Computer_SoftwareLicense' => 'Item_SoftwareLicense',
    'Computer_SoftwareVersion' => 'Item_SoftwareVersion',
];
foreach ($mapping as $old_itemtype => $new_itemtype) {
    $migration->renameItemtype($old_itemtype, $new_itemtype, false);
}
