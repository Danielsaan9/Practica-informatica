<?php

namespace Glpi\Api\HL;

use Glpi\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * This class wraps a Symfony {@link StreamedResponse} to allow it to be used in the API, which expects a PSR-7 response.
 *
 * @internal Intended to be removed in the future, when the API requests/responses use Symfony's classes which aren't PSR-7 compatible.
 */
final class StreamedResponseWrapper extends Response
{
    public function __construct(
        private StreamedResponse $symfony_response
    ) {
        parent::__construct(
            $symfony_response->getStatusCode(),
            $symfony_response->headers->all(),
            '', // The content is always returned as 'false' in a StreamedResponse, so we set it to an empty string here.
        );
    }

    /**
     * Get the underlying Symfony StreamedResponse with some of its data synced with the PSR-7 response.
     * @return StreamedResponse
     */
    public function getSymfonyResponse(): StreamedResponse
    {
        $this->symfony_response->setStatusCode($this->getStatusCode());
        foreach ($this->getHeaders() as $header => $values) {
            foreach ($values as $value) {
                $this->symfony_response->headers->set($header, $value);
            }
        }
        return $this->symfony_response;
    }
}
