<?php

/**
 * @var Migration $migration
 * @var array $ADDTODISPLAYPREF
 * @var DBmysql $DB
 */
$default_charset = DBConnection::getDefaultCharset();
$default_collation = DBConnection::getDefaultCollation();
$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

if (!$DB->tableExists('glpi_webhooks')) {
    $query = "CREATE TABLE `glpi_webhooks` (
      `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
      `entities_id` int {$default_key_sign} NOT NULL DEFAULT '0',
      `is_recursive` tinyint NOT NULL DEFAULT '0',
      `name` varchar(255) DEFAULT NULL,
      `comment` text,
      `webhookcategories_id` int {$default_key_sign} NOT NULL DEFAULT '0',
      `itemtype` varchar(255) DEFAULT NULL,
      `event` varchar(255) DEFAULT NULL,
      `payload` longtext,
      `use_default_payload` tinyint NOT NULL DEFAULT '1',
      `custom_headers` text,
      `url` text DEFAULT NULL,
      `secret` text,
      `use_cra_challenge` tinyint NOT NULL DEFAULT '0',
      `http_method` varchar(255) DEFAULT 'POST',
      `sent_try` tinyint NOT NULL DEFAULT '3',
      `expiration` int NOT NULL DEFAULT '0',
      `is_active` tinyint NOT NULL DEFAULT '0',
      `save_response_body` tinyint NOT NULL DEFAULT '0',
      `log_in_item_history` tinyint NOT NULL DEFAULT '0',
      `date_creation` timestamp NULL DEFAULT NULL,
      `date_mod` timestamp NULL DEFAULT NULL,
      `use_oauth` tinyint NOT NULL DEFAULT '0',
      `oauth_url` varchar(255) DEFAULT NULL,
      `clientid` varchar(255) DEFAULT NULL,
      `clientsecret` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `name` (`name`),
      KEY `is_active` (`is_active`),
      KEY `entities_id` (`entities_id`),
      KEY `is_recursive` (`is_recursive`),
      KEY `use_cra_challenge` (`use_cra_challenge`),
      KEY `date_creation` (`date_creation`),
      KEY `date_mod` (`date_mod`),
      KEY `webhookcategories_id` (`webhookcategories_id`)
    ) ENGINE = InnoDB ROW_FORMAT = DYNAMIC DEFAULT CHARSET = {$default_charset} COLLATE = {$default_collation};";
    $DB->doQuery($query);
} else {
    $migration->changeField('glpi_webhooks', 'url', 'url', 'text');
}

if (!$DB->fieldExists('glpi_webhooks', 'webhookcategories_id')) {
    // Dev migration
    $migration->addField('glpi_webhooks', 'webhookcategories_id', 'fkey', [
        'after' => 'comment',
    ]);
    $migration->addKey('glpi_webhooks', 'webhookcategories_id', 'webhookcategories_id');
}

if (!$DB->tableExists('glpi_webhookcategories')) {
    $query = "CREATE TABLE `glpi_webhookcategories` (
      `id` int {$default_key_sign} NOT NULL AUTO_INCREMENT,
      `name` varchar(255) DEFAULT NULL,
      `comment` text,
      `webhookcategories_id` int {$default_key_sign} NOT NULL DEFAULT '0',
      `completename` text,
      `level` int NOT NULL DEFAULT '0',
      `ancestors_cache` longtext,
      `sons_cache` longtext,
      `date_mod` timestamp NULL DEFAULT NULL,
      `date_creation` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `unicity` (`webhookcategories_id`,`name`),
      KEY `name` (`name`),
      KEY `date_mod` (`date_mod`),
      KEY `date_creation` (`date_creation`),
      KEY `level` (`level`)
    ) ENGINE = InnoDB ROW_FORMAT = DYNAMIC DEFAULT CHARSET = {$default_charset} COLLATE = {$default_collation};";
    $DB->doQuery($query);
}

$ADDTODISPLAYPREF[Webhook::class] = [3, 4, 5];

if (!$DB->tableExists('glpi_queuedwebhooks')) {
    $query = "CREATE TABLE `glpi_queuedwebhooks` (
      `id` int unsigned NOT NULL AUTO_INCREMENT,
      `itemtype` varchar(100) DEFAULT NULL,
      `items_id` int unsigned NOT NULL DEFAULT '0',
      `entities_id` int unsigned NOT NULL DEFAULT '0',
      `is_deleted` tinyint NOT NULL DEFAULT '0',
      `sent_try` int NOT NULL DEFAULT '0',
      `webhooks_id` int unsigned NOT NULL DEFAULT '0',
      `url` text DEFAULT NULL,
      `create_time` timestamp NULL DEFAULT NULL,
      `send_time` timestamp NULL DEFAULT NULL,
      `sent_time` timestamp NULL DEFAULT NULL,
      `headers` text,
      `body` longtext,
      `event` varchar(255) DEFAULT NULL,
      `last_status_code` int DEFAULT NULL,
      `save_response_body` tinyint NOT NULL DEFAULT '0',
      `response_body` longtext,
      `http_method` varchar(255) DEFAULT 'POST',
      PRIMARY KEY (`id`),
      KEY `item` (`itemtype`,`items_id`),
      KEY `entities_id` (`entities_id`),
      KEY `webhooks_id` (`webhooks_id`),
      KEY `is_deleted` (`is_deleted`),
      KEY `sent_try` (`sent_try`),
      KEY `create_time` (`create_time`),
      KEY `send_time` (`send_time`),
      KEY `sent_time` (`sent_time`)
    ) ENGINE = InnoDB ROW_FORMAT = DYNAMIC DEFAULT CHARSET = {$default_charset} COLLATE = {$default_collation};";
    $DB->doQuery($query);
} else {
    $migration->changeField('glpi_queuedwebhooks', 'url', 'url', 'text');
}

// Entity, ID, Webhook, Itemtype, Items ID, URL, Creation date
$ADDTODISPLAYPREF[QueuedWebhook::class] = [80, 2, 22, 20, 21, 7, 30, 16];

$migration->addCrontask(
    'QueuedWebhook',
    'queuedwebhook',
    MINUTE_TIMESTAMP,
    param: 50, // Limit for webhooks to send per cron task run
);

$migration->addCrontask(
    'QueuedWebhook',
    'queuedwebhookclean',
    DAY_TIMESTAMP,
    param: 30, // webhooks older than 30 days will be deleted
    options: [
        'hourmin' => 0,
        'hourmax' => 6,
    ]
);
