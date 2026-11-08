<?php

namespace Glpi\CalDAV\Backend;

use Sabre\DAV\Auth\Backend\AbstractBasic;
use Session;

/**
 * Basic authentication backend for CalDAV server.
 *
 * @since 9.5.0
 */
class Auth extends AbstractBasic
{
    protected $principalPrefix = Principal::PREFIX_USERS . '/';

    protected function validateUserPass($username, $password)
    {
        // TODO Enforce security by accepting here only CalDAV application dedicated password

        $auth = new \Auth();
        if ($auth->validateLogin($username, $password, true)) {
            Session::init($auth);
            return true;
        }

        return false;
    }
}
