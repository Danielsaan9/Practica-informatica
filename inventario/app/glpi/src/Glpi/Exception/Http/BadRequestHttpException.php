<?php

namespace Glpi\Exception\Http;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException as BaseException;

class BadRequestHttpException extends BaseException implements HttpExceptionInterface
{
    use HttpExceptionTrait;
}
