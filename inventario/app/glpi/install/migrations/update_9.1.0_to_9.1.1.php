<?php

/**
 * Update from 9.1 to 9.1.1
 *
 * @return bool
 **/
function update910to911()
{
    /**
     * @var DBmysql $DB
     * @var Migration $migration
     */
    global $DB, $migration;

    $updateresult     = true;

    $migration->setVersion('9.1.1');

    // rectify missing right in 9.1 update
    if (countElementsInTable("glpi_profilerights", ['name' => 'license']) == 0) {
        $prights = $DB->request(['FROM' => 'glpi_profilerights', 'WHERE' => ['name' => 'software']]);
        foreach ($prights as $profrights) {
            $DB->insert(
                "glpi_profilerights",
                [
                    'id'           => null,
                    'profiles_id'  => $profrights['profiles_id'],
                    'name'         => "license",
                    'rights'       => $profrights['rights'],
                ]
            );
        }
    }

    //put you migration script here

    // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
