<?php

namespace Glpi\ContentTemplates\Parameters;

use CommonDBTM;
use Glpi\ContentTemplates\Parameters\ParametersTypes\ParameterTypeInterface;

/**
 * Twig content templates parameters definition interface.
 *
 * @since 10.0.0
 */
interface TemplatesParametersInterface
{
    /**
     * Get default node name to use for this class.
     *
     * @return string
     */
    public static function getDefaultNodeName(): string;

    /**
     * Get object label to use for this class.
     *
     * @return string
     */
    public static function getObjectLabel(): string;

    /**
     * Get values for a given item, used for template rendering
     *
     * @param CommonDBTM $item
     *
     * @return array
     */
    public function getValues(CommonDBTM $item): array;

    /**
     * To be defined in each subclasses, define all available parameters for one or more itemtypes.
     * These parameters information are meant to be used for autocompletion on the client side.
     *
     * @return ParameterTypeInterface[]
     */
    public function getAvailableParameters(): array;
}
