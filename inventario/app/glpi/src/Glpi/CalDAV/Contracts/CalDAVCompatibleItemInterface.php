<?php

namespace Glpi\CalDAV\Contracts;

use Sabre\VObject\Component\VCalendar;

/**
 * Interface used to define methods that must be implemented by items served by CalDAV server.
 *
 * @since 9.5.0
 */
interface CalDAVCompatibleItemInterface
{
    /**
     * Get group items as VCalendar documents.
     *
     * @param int $groups_id
     *
     * @return VCalendar[]
     */
    public static function getGroupItemsAsVCalendars($groups_id);

    /**
     * Get user items as VCalendar documents.
     *
     * @param int $users_id
     *
     * @return VCalendar[]
     */
    public static function getUserItemsAsVCalendars($users_id);

    /**
     * Get current item as a VCalendar document.
     *
     * @return null|VCalendar
     *
     * @see https://tools.ietf.org/html/rfc2445
     */
    public function getAsVCalendar();

    /**
     * Get input array from a VCalendar object.
     *
     * @param VCalendar $vcalendar
     *
     * @return array
     *
     * @see https://tools.ietf.org/html/rfc2445
     */
    public function getInputFromVCalendar(VCalendar $vcalendar);
}
