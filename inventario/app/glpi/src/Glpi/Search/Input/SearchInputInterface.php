<?php

namespace Glpi\Search\Input;

use CommonDBTM;

/**
 *
 * @internal Not for use outside {@link Search} class and the "Glpi\Search" namespace.
 */
interface SearchInputInterface
{
    /**
     * Print generic search form
     *
     * Params need to parsed before using Search::manageParams function
     *
     * @param string $itemtype  Type to display the form
     * @param array  $params    Array of parameters may include sort, is_deleted, criteria, metacriteria
     *
     * @return void
     **/
    public static function showGenericSearch(string $itemtype, array $params);

    public static function cleanParams(array $params): array;

    /**
     * Completion of the URL $_GET values with the $_SESSION values or define default values
     *
     * @param class-string<CommonDBTM> $itemtype Item type to manage
     * @param array   $params          Params to parse
     * @param bool $usesession      Use data saved in the session (true by default)
     * @param bool $forcebookmark   Force trying to load parameters from default bookmark:
     *                                  used for global search (false by default)
     *
     * @return array parsed params
     **/
    public static function manageParams($itemtype, $params = [], $usesession = true, $forcebookmark = false): array;
}
