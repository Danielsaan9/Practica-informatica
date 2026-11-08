<?php

namespace Glpi\Http;

use JsonException;

class JSONResponse extends Response
{
    public function __construct(?array $content = [], int $status = 200, array $headers = [])
    {
        $additional_headers['Content-Type'] = 'application/json';
        $raw_content = null;
        if ($content !== null) {
            try {
                $raw_content = json_encode($content, JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                $status = 500;
                $headers = [];
            }
        }
        $headers = array_merge($headers, $additional_headers);
        parent::__construct($status, $headers, $raw_content);
    }
}
