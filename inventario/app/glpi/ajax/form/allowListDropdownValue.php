<?php

include(__DIR__ . '../../getAbstractRightDropdownValue.php');

use Glpi\Form\AccessControl\ControlType\AllowListDropdown;
use Glpi\Form\Form;

// This dropdown is used to configure form's access controls.
// It should only be visible to users with the right to view form's access controls.
Session::checkRight(Form::$rightname, READ);
show_rights_dropdown(AllowListDropdown::class);
