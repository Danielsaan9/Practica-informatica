<?php

/**
 * @var Migration $migration
 */
$migration->addRightByInterface('reminder_public', Reminder::PERSONAL, 'central');
$migration->addRightByInterface('rssfeed_public', RSSFeed::PERSONAL, 'central');
