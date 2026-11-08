<?php

namespace Glpi\Api\HL\Middleware;

use Glpi\Http\JSONResponse;
use Glpi\Http\Response;
use JsonException;
use SimpleXMLElement;

class ResultFormatterMiddleware extends AbstractMiddleware implements ResponseMiddlewareInterface
{
    public function process(MiddlewareInput $input, callable $next): void
    {
        if (!$input->response instanceof JSONResponse) {
            $next($input);
            return;
        }
        try {
            $data = json_decode($input->response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $next($input);
            return;
        }
        if (strtolower($input->request->getHeaderLine('Accept')) === 'text/csv') {
            $input->response = new Response(200, [
                'Content-Type' => 'text/csv',
            ], $this->formatCSV($data));
        } elseif (strtolower($input->request->getHeaderLine('Accept')) === 'application/xml') {
            $input->response = new Response(200, [
                'Content-Type' => 'application/xml',
            ], $this->formatXML($data));
        }
        $next($input);
    }

    private function formatCSV(array $data): string
    {
        $columns = [];
        $rows = [];
        $fn_get_data = static function ($data, $prefix, $row = []) use (&$columns, &$fn_get_data) {
            foreach ($data as $key => $value) {
                $full_key = $prefix !== '' ? "{$prefix}.{$key}" : $key;
                if (is_array($value)) {
                    $row = $fn_get_data($value, $full_key, $row);
                } else {
                    $columns[$full_key] = $full_key;
                    $row[$full_key] = $value;
                }
            }
            return $row;
        };

        if (!array_is_list($data)) {
            $data = [$data];
        }
        foreach ($data as $result_row) {
            $rows[] = $fn_get_data($result_row, '');
        }
        $csv = implode(',', array_map(static fn($value) => '"' . str_replace('"', '""', $value) . '"', $columns)) . "\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map(static fn($value) => '"' . str_replace('"', '""', $value) . '"', $row)) . "\n";
        }
        return $csv;
    }

    private function formatXML(array $data): string
    {
        $xml = new SimpleXMLElement('<root/>');
        $fn_get_data = static function ($data, $xml) use (&$fn_get_data) {
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    if (is_array($value)) {
                        $fn_get_data($value, $xml->addChild($key));
                    } else {
                        $xml->addChild($key, $value);
                    }
                }
            }
            return $xml;
        };
        if (!array_is_list($data)) {
            $data = [$data];
        }
        foreach ($data as $result_row) {
            $fn_get_data($result_row, $xml->addChild('row'));
        }
        return $xml->asXML();
    }
}
