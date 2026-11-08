<?php

namespace Glpi\Features;

use CommonDBTM;
use Glpi\Application\View\TemplateRenderer;
use ProjectTeam;

/**
 * Trait for itemtypes that can have a team
 * @since 10.0.0
 */
trait Teamwork
{
    /**
     * @see TeamworkInterface::getTeamMemberForm()
     */
    public static function getTeamMemberForm(CommonDBTM $item): string
    {
        $members_types = ProjectTeam::$available_types;

        return TemplateRenderer::getInstance()->render('components/kanban/teammember.html.twig', [
            'item' => $item,
            'members_types' => $members_types,
        ]);
    }
}
