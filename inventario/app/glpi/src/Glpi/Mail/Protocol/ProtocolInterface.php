<?php

namespace Glpi\Mail\Protocol;

interface ProtocolInterface
{
    /**
     * Do not validate SSL certificate
     *
     * @param  bool $novalidatecert Set to true to disable certificate validation
     *
     * @return self
     */
    public function setNoValidateCert(bool $novalidatecert);

    /**
     * Open connection to server.
     *
     * @param  string      $host  hostname or IP address of POP3 server
     * @param  int|null    $port  server port, null value with fallback to default port
     * @param  string|bool $ssl   use 'SSL', 'TLS' or false
     *
     * @return void
     */
    public function connect($host, $port = null, $ssl = false);

    /**
     * Login to server.
     *
     * @param  string $user      username
     * @param  string $password  password
     *
     * @return bool
     */
    public function login($user, $password);
}
