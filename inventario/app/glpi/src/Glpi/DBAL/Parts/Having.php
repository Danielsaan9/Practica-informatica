<?php

namespace Glpi\DBAL\Parts;

class Having extends Where
{
    protected ?string $clause = 'HAVING';
}
