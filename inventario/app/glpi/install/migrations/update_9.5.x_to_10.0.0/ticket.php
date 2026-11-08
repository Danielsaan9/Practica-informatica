<?php

/* Remove global_validation field from templates (should not be defined manually). */
foreach (['glpi_tickettemplatemandatoryfields', 'glpi_tickettemplatepredefinedfields'] as $table) {
    /**
     * @var DBmysql $DB
     * @var Migration $migration
     */
    $migration->addPostQuery(
        $DB->buildDelete(
            $table,
            [
                'num' => 52, // global_validation
            ]
        )
    );
}
/* /Remove global_validation field from templates (should not be defined manually). */
/* Add dedicated right for ITILFollowupTemplate */
/** @var Migration $migration */
$migration->addRight('itilfollowuptemplate', ALLSTANDARDRIGHT, ['dropdown' => UPDATE]);
/* Add dedicated right for ITILFollowupTemplate */
