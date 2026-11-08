<?php

namespace Glpi\Search\Output;

use CommonGLPI;

/**
 *
 * @internal Not for use outside {@link Search} class and the "Glpi\Search" namespace.
 */
abstract class AbstractSearchOutput
{
    /**
     * Modify the search parameters before the search is executed.
     *
     * This is useful if some criteria need injected such as the Location in the case of the Map output.
     * This is called after the search input form is shown, so any new criteria will be hidden.
     * @param class-string<CommonGLPI> $itemtype
     * @param array $params
     * @return array
     */
    public static function prepareInputParams(string $itemtype, array $params): array
    {
        return $params;
    }

    /**
     * Display the search results
     *
     * @param array $data Array of search data prepared to get data
     * @param array $params The original search parameters
     *
     * @return void|false
     **/
    abstract public function displayData(array $data, array $params = []);

    public function canDisplayResultsContainerWithoutExecutingSearch(): bool
    {
        return false;
    }
}
