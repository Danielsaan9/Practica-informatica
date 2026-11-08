<?php

namespace Glpi\Form\Export\Result;

enum ImportError
{
    case MISSING_DATA_REQUIREMENT;
    case MISSING_CUSTOM_TYPE_REQUIREMENT;
    case MISSING_PLUGIN_REQUIREMENT;
}
