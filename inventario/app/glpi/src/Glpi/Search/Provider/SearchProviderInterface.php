<?php

namespace Glpi\Search\Provider;

/**
 * Interface for all search providers.
 *
 * Search providers query one or more types of data sources to get matching data.
 *
 * @internal Not for use outside {@link Search} class and the "Glpi\Search" namespace.
 */
interface SearchProviderInterface
{
    public static function prepareData(array &$data, array $options): array;

    /**
     * Construct SQL request depending on search parameters
     *
     * Add to data array a field sql containing an array of requests :
     *      search : request to get items limited to wanted ones
     *      count : to count all items based on search criterias
     *                    may be an array a request : need to add counts
     *                    maybe empty : use search one to count
     *
     * @param array $data Array of search data prepared to generate SQL
     * @return false|void
     */
    public static function constructSQL(array &$data);

    /**
     * Retrieve data from DB : construct data array containing columns definitions and rows data
     *
     * add to data array a field data containing :
     *      cols : columns definition
     *      rows : rows data
     *
     * @param array   $data      array of search data prepared to get data
     * @param bool $onlycount If we just want to count results
     *
     * @return void|false
     **/
    public static function constructData(array &$data, $onlycount = false);
}
