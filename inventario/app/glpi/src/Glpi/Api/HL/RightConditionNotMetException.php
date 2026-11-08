<?php

namespace Glpi\Api\HL;

/**
 * An exception thrown specifically when a right condition is known to be a failure without needing to perform any SQL query.
 * For example, if a user has no Ticket permission then we know they cannot read any Tickets.
 * Instead of performing a SQL query with a condition that always resolve to no records, we can fail-fast and use an empty iterator result.
 */
class RightConditionNotMetException extends APIException {}
