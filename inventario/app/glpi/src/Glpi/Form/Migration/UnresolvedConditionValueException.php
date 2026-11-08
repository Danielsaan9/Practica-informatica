<?php

namespace Glpi\Form\Migration;

use Exception;

// Mirrors FallbackToAnotherOperatorException: signal "no match" via an
// exception instead of widening convertConditionValue()'s return type.
final class UnresolvedConditionValueException extends Exception {}
