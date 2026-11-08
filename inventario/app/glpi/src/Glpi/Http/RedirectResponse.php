<?php

namespace Glpi\Http;

use InvalidArgumentException;
use Override;

class RedirectResponse extends \Symfony\Component\HttpFoundation\RedirectResponse
{
    #[Override()]
    public function setTargetUrl(string $url): static
    {
        if ('' === $url) {
            throw new InvalidArgumentException('Cannot redirect to an empty URL.');
        }

        $this->targetUrl = $url;

        $this->setContent(
            \sprintf(
                '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="0;url=\'%1$s\'" />

        <title>%2$s</title>
    </head>
    <body></body>
</html>',
                \htmlescape($url),
                \htmlescape(\sprintf('Redirecting to %s...', $url))
            )
        );

        $this->headers->set('Location', $url);
        $this->headers->set('Content-Type', 'text/html; charset=utf-8');

        return $this;
    }
}
