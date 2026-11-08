<?php

namespace Glpi\Exception\Http;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException as BaseException;

class AccessDeniedHttpException extends BaseException implements HttpExceptionInterface
{
    use HttpExceptionTrait;
}
