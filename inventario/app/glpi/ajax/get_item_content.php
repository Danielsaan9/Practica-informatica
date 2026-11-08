<?php

use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\Http\NotFoundHttpException;
use Glpi\RichText\RichText;

/*
 * Ajax tooltip endpoint for CommonITILObjects
 */

// Read parameters
$itemtype = $_GET['itemtype'] ?? null;
$items_id = $_GET['items_id'] ?? null;

// Validate mandatory parameters
if (is_null($itemtype) || is_null($items_id)) {
    throw new BadRequestHttpException("Missing required parameters");
}

// Validate itemtype (only CommonITILObject allowed for now)
if (!is_a($itemtype, CommonITILObject::class, true)) {
    throw new BadRequestHttpException("Invalid itemtype");
}
$item = new $itemtype();

// Validate item
if (
    !$item->getFromDB($items_id)
    || !$item->can($item->getID(), READ)
    || !$item->isField('content')
) {
    throw new NotFoundHttpException("Item not found");
}

// Display content
header('Content-type: text/html; charset=UTF-8');
echo RichText::getEnhancedHtml($item->fields['content'], [
    'images_gallery' => false, // Don't show photoswipe gallery
]);
