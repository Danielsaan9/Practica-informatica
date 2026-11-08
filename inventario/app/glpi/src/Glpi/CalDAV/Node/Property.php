<?php

namespace Glpi\CalDAV\Node;

/**
 * Calendar node properties constants.
 *
 * @since 9.5.0
 */
class Property
{
    public const CAL_COLOR                = '{http://apple.com/ns/ical/}calendar-color';
    public const CAL_DESCRIPTION          = '{urn:ietf:params:xml:ns:caldav}calendar-description';
    public const CAL_SUPPORTED_COMPONENTS = '{urn:ietf:params:xml:ns:caldav}supported-calendar-component-set';
    public const CAL_USER_TYPE            = '{urn:ietf:params:xml:ns:caldav}calendar-user-type';
    public const DISPLAY_NAME             = '{DAV:}displayname';
    public const PRIMARY_EMAIL            = '{http://sabredav.org/ns}email-address';
    public const RESOURCE_TYPE            = '{DAV:}resourcetype';
    public const USERNAME                 = '{http://glpi-project.org/ns}username';
}
