<?php

Html::header_nocache();

$domainrecordtype = new DomainRecordType();
if (
    !array_key_exists('domainrecordtypes_id', $_REQUEST)
    || !$domainrecordtype->getFromDB($_REQUEST['domainrecordtypes_id'])
) {
    $domainrecordtype->getEmpty();
}

$domainrecordtype->showDataAjaxForm($_REQUEST['str_input_id'] ?? '', $_REQUEST['obj_input_id'] ?? '');
