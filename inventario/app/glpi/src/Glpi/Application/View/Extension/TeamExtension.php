<?php

namespace Glpi\Application\View\Extension;

use CommonDBTM;
use Glpi\Features\TeamworkInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @since 10.0.0
 */
class TeamExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('team_role_name', [$this, 'getTeamRoleName']),
        ];
    }

    /**
     * @param class-string<CommonDBTM> $itemtype
     * @param int $role
     * @param int $nb
     *
     * @return string
     */
    public function getTeamRoleName($itemtype, int $role, int $nb = 1): string
    {
        if (is_a($itemtype, TeamworkInterface::class, true)) {
            return $itemtype::getTeamRoleName($role, $nb);
        }
        return '';
    }
}
