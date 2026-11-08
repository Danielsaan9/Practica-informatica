<?php

namespace Glpi\ContentTemplates;

use Glpi\ContentTemplates\Parameters\ChangeParameters;
use Glpi\ContentTemplates\Parameters\ParametersTypes\AttributeParameter;
use Glpi\ContentTemplates\Parameters\ParametersTypes\ObjectParameter;
use Glpi\ContentTemplates\Parameters\ProblemParameters;
use Glpi\ContentTemplates\Parameters\TicketParameters;
use ITILSolution;
use SolutionTemplate;
use TaskTemplate;
use TicketTemplate;

/**
 * Helper class to get some predefined groups of twig parameters
 */
class ParametersPreset
{
    /**
     * Twig parameters that will be avaiable in solution/task/followup form
     */
    public const ITIL_CHILD_TEMPLATE = 'itilchildtemplate';

    /**
     * Twig parameters that will be available in the solution massive actions
     * form for tickets
     */
    public const TICKET_SOLUTION = 'ticketsolution';

    /**
     * Get parameters from their unique key (one of the contant above).
     * This is useful when sending data through a form, the controller can
     * receive a key value and fetch the parameters with this method.
     *
     * @param string $key
     *
     * @return array
     */
    public static function getByKey(string $key): array
    {
        switch ($key) {
            case self::ITIL_CHILD_TEMPLATE:
                return self::getForAbstractTemplates();

            case self::TICKET_SOLUTION:
                return self::getForTicketSolution();

            default:
                return [];
        }
    }

    /**
     * Get context to be displayed in the variable list page for each keys
     *
     * @param string $key
     *
     * @return string
     */
    public static function getContextByKey(string $key): string
    {
        switch ($key) {
            case self::ITIL_CHILD_TEMPLATE:
                $types = [
                    TicketTemplate::getTypeName(1),
                    TaskTemplate::getTypeName(1),
                    SolutionTemplate::getTypeName(1),
                ];

                return implode("/", $types);

            case self::TICKET_SOLUTION:
                return ITILSolution::getTypeName(1);

            default:
                return "";
        }
    }

    /**
     * Twig parameters that will be avaiable in solution/task/followup form
     *
     * @return array
     */
    public static function getForAbstractTemplates(): array
    {
        return [
            new AttributeParameter('itemtype', __('Itemtype')),
            new ObjectParameter(new TicketParameters()),
            new ObjectParameter(new ChangeParameters()),
            new ObjectParameter(new ProblemParameters()),
        ];
    }

    /**
     * Twig parameters that will be available in the solution massive actions
     * form for tickets
     *
     * @return array
     */
    public static function getForTicketSolution(): array
    {
        return [
            new AttributeParameter('itemtype', __('Itemtype')),
            new ObjectParameter(new TicketParameters()),
        ];
    }
}
