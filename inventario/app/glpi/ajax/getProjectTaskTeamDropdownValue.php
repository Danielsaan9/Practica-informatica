<?php

include(__DIR__ . '/getAbstractRightDropdownValue.php');

// Only users who can read projects are allowed to use this dropdown
Session::checkRightsOr(Project::$rightname, [Project::READALL, Project::READMY]);

show_rights_dropdown(ProjectTaskTeamDropdown::class);
