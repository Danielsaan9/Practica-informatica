<?php

namespace Glpi\Exception\Http;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException as BaseException;

class UnprocessableEntityHttpException extends BaseException implements HttpExceptionInterface
{
    use HttpExceptionTrait;
}
