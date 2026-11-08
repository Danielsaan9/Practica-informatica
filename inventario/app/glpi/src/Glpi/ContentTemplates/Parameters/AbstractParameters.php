<?php

namespace Glpi\ContentTemplates\Parameters;

use CommonDBTM;

/**
 * Abstract twig content templates parameters definition.
 *
 * @since 10.0.0
 */
abstract class AbstractParameters implements TemplatesParametersInterface
{
    /**
     * To by defined in each subclasses, get the exposed values for a given item
     * These values will be used as parameters when rendering a twig template.
     *
     * Result will be returned by `self::getValues()`.
     *
     * @param CommonDBTM $item
     *
     * @return array
     */
    abstract protected function defineValues(CommonDBTM $item): array;

    /**
     * Get supported classes by this parameter type.
     *
     * @return array
     */
    abstract protected function getTargetClasses(): array;

    public function getValues(CommonDBTM $item): array
    {
        $valid_class = false;
        foreach ($this->getTargetClasses() as $class) {
            if ($item instanceof $class) {
                $valid_class = true;
                break;
            }
        }

        if (!$valid_class) {
            trigger_error(get_class($item) . " is not allowed for this parameter type.", E_USER_WARNING);
            return [];
        }

        return $this->defineValues($item);
    }
}
