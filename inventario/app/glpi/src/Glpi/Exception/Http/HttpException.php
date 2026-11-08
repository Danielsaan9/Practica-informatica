<?php

namespace Glpi\Exception\Http;

use Symfony\Component\HttpKernel\Exception\HttpException as BaseException;

class HttpException extends BaseException implements HttpExceptionInterface
{
    use HttpExceptionTrait;
}
