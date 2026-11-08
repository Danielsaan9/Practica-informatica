<?php

/**
 * @var Migration $migration
 */

// Add configuration option to control document attachment for anonymous users in notifications
$migration->addConfig(['attach_documents_to_notifications_for_anonymous' => 0]);
