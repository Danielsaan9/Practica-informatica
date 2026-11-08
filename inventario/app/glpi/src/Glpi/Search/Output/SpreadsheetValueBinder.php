<?php

namespace Glpi\Search\Output;

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

/**
 * Custom value binder for spreadsheet export.
 *
 * This class extends the default value binder to ensure that values that look like formulas are treated as strings, preventing them from being evaluated as formulas in the spreadsheet.
 */
class SpreadsheetValueBinder extends DefaultValueBinder
{
    public static function dataTypeForValue(mixed $value): string
    {
        $datatype = parent::dataTypeForValue($value);
        return $datatype === DataType::TYPE_FORMULA ? DataType::TYPE_STRING : $datatype;
    }
}
