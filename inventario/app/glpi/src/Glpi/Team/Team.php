<?php

namespace Glpi\Team;

use CommonITILActor;

/**
 * Class for a team that may contain members of multiple types (users, groups, etc) of various roles.
 * @since 10.0.0
 */
final class Team
{
    /**
     * A team member that is requesting the ticket, change, problem, etc.
     */
    public const ROLE_REQUESTER = CommonITILActor::REQUESTER;

    /**
     * A team member that is watching the ticket, change, problem, etc.
     */
    public const ROLE_OBSERVER = CommonITILActor::OBSERVER;

    /**
     * A team member that is assigned to the ticket, change, problem, etc.
     */
    public const ROLE_ASSIGNED = CommonITILActor::ASSIGN;

    /**
     * A team member who is an owner of the item.
     * Typically, this is used for Projects (Project managers).
     */
    public const ROLE_OWNER = 5;

    /**
     * The member who "wrote" or submitted a ticket, change, or problem.
     */
    public const ROLE_WRITER = 6;

    /**
     * A general team member for when a more specific role is not applicable.
     * Typically, this is used for Projects for anyone who is not the Project manager.
     */
    public const ROLE_MEMBER = 7;
}
