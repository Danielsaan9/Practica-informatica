<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$migration->addPostQuery(
    $DB->buildUpdate(
        Config::getTable(),
        [
            'value' => 1, // MAIL_SMTP
        ],
        [
            'context' => 'core',
            'name' => 'smtp_mode',
            // deprecated MAIL_SMTPTLS
            // `symfony/mailer` uses STARTTLS automatically when the server supports it
            // and uses the `ssl://` scheme automatically when the port 465 is used
            'value' => 3,
        ]
    )
);
