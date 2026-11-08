<?php

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

$ra = new RuleAction();

$ra->displayActionSelectPattern($_POST);
