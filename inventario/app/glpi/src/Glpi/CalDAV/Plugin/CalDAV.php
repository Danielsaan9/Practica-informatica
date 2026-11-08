<?php

namespace Glpi\CalDAV\Plugin;

use Glpi\CalDAV\Backend\Calendar;
use Glpi\CalDAV\Traits\CalDAVUriUtilTrait;
use Group;
use Sabre\CalDAV\Plugin;
use Sabre\DAV\INode;
use Sabre\DAV\IProperties;
use Sabre\DAV\PropFind;
use User;

/**
 * CalDAV plugin for CalDAV server.
 *
 * @since 9.5.0
 */
class CalDAV extends Plugin
{
    use CalDAVUriUtilTrait;

    public function getCalendarHomeForPrincipal($principalUrl)
    {

        $calendar_uri = null;

        $principal_itemtype = $this->getPrincipalItemtypeFromUri($principalUrl);
        switch ($principal_itemtype) {
            case Group::class:
                $calendar_uri = Calendar::PREFIX_GROUPS . '/' . $this->getGroupIdFromPrincipalUri($principalUrl);
                break;
            case User::class:
                $calendar_uri = Calendar::PREFIX_USERS . '/' . $this->getUsernameFromPrincipalUri($principalUrl);
                break;
        }

        return $calendar_uri;
    }

    /**
     * @param PropFind $propFind
     * @param INode $node
     *
     * @return void
     */
    public function propFind(PropFind $propFind, INode $node)
    {

        // Return any requested property as long as it is defined in node.
        if ($node instanceof IProperties) {
            $properties = $node->getProperties([]);
            foreach ($properties as $property_name => $property_value) {
                $propFind->handle($property_name, $property_value);
            }
        }

        parent::propFind($propFind, $node);
    }
}
