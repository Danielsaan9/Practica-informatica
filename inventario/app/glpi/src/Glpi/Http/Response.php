<?php

namespace Glpi\Http;

/**
 * @since 10.0.0
 */
class Response extends \GuzzleHttp\Psr7\Response
{
    /**
     * "application/json" content type.
     */
    public const CONTENT_TYPE_JSON = 'application/json';

    /**
     * "text/html" content type.
     */
    public const CONTENT_TYPE_TEXT_HTML = 'text/html';

    /**
     * "text/plain" content type.
     */
    public const CONTENT_TYPE_TEXT_PLAIN = 'text/plain';
}
