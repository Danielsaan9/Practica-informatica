<?php

use Glpi\Exception\Http\NotFoundHttpException;

if (isset($_POST['id'])) {
    $stencil = Stencil::getStencilFromID($_POST['id']);

    if (!$stencil) {
        throw new NotFoundHttpException('Stencil not found');
    }

    $stencil->check($_POST['id'], READ);

    if (isset($_POST['update'])) {
        $stencil->check($_POST['id'], UPDATE);
        $stencil->update($_POST);
    } elseif (isset($_POST['add-new-zone'])) {
        $stencil->check($_POST['id'], UPDATE);
        $stencil->addNewZones($_POST);
    } elseif (isset($_POST['remove-zone'])) {
        $stencil->check($_POST['id'], UPDATE);
        $stencil->removeZones($_POST);
    } elseif (isset($_POST['reset-zone'])) {
        $stencil->check($_POST['id'], UPDATE);
        $stencil->resetZones($_POST);
    }
}
