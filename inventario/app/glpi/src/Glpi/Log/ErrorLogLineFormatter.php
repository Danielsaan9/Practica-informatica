<?php

namespace Glpi\Log;

final class ErrorLogLineFormatter extends AbstractLogLineFormatter
{
    public function __construct()
    {
        parent::__construct(
            format: "[%datetime%] %channel%.%level_name%:   *** %message%%context.exception%\n",
            dateFormat: 'Y-m-d H:i:s',
            allowInlineLineBreaks: true,
            ignoreEmptyContextAndExtra: true,
        );
    }
}
