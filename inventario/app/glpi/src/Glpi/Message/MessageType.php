<?php

namespace Glpi\Message;

enum MessageType: string
{
    case Error = 'error';
    case Warning = 'warning';
    case Notice = 'notice';
    case Success = 'success';
    case Debug = 'debug';
}
