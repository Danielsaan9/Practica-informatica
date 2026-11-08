<?php

namespace Glpi\Exception\Http;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as BaseException;

class NotFoundHttpException extends BaseException implements HttpExceptionInterface
{
    use HttpExceptionTrait;
}
