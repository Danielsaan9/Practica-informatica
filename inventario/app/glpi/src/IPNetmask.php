<?php

/// Class IPNetmask
/// since version 0.84
class IPNetmask extends IPAddress
{
    protected static bool $notable = true;


    /**
     * @param string|array|IPNetmask $ipnetmask
     * @param int $version
     **/
    public function __construct($ipnetmask = '', $version = 0)
    {

        // First, be sure that the parent is correctly initialised
        parent::__construct();

        // If $ipnetmask is empty, then, empty netmask!
        if ($ipnetmask != '') {
            // If $ipnetmask if an IPNetmask, then just clone it
            if ($ipnetmask instanceof IPNetmask) {
                $this->version = $ipnetmask->version;
                $this->textual = $ipnetmask->textual;
                $this->binary  = $ipnetmask->binary;
                $this->fields  = $ipnetmask->fields;
            } else {
                // Else, check a binary then a string
                if (!$this->setAddressFromBinary($ipnetmask)) {
                    $this->setNetmaskFromString($ipnetmask, $version);
                }
            }
        }
    }


    public static function getTypeName($nb = 0)
    {
        return _n('Subnet mask', 'Subnet masks', $nb);
    }


    /**
     * \brief Create a Netmask from string
     *
     * Create a binary Netmask from dot notation (for instance : 255.255.255.0) or
     * integer (for instance /24). Rely on setAddressFromString()
     *
     * @param string $netmask netmask defined as textual
     * @param int|string $version =4 or =6 : version of IP protocol
     *
     * @return bool false if the netmask is not valid or if it does not correspond to version
     */
    public function setNetmaskFromString($netmask, $version)
    {
        if (is_numeric($netmask)) {
            if ($netmask < 0) {
                return false;
            }
            // Transform the number of bits to IPv6 netmasks ...
            $nbBits = $netmask + (($version == 4) ? 96 : 0);
            if ($nbBits > 128) {
                return false;
            }
            $bits          = str_repeat("1", $nbBits) . str_repeat("0", 128 - $nbBits);
            $this->version = $version;
            $this->textual = $netmask;
            $this->binary  = [];
            for ($i = 0; $i  < 4; $i++) {
                $localBits      = substr($bits, 32 * $i, 32);
                $this->binary[] = bindec($localBits);
            }
        } else {
            if (!$netmask = $this->setAddressFromString($netmask)) {
                return false;
            }
            if ($version != $this->getVersion()) {
                return false;
            }
            if ($version == 4) {
                for ($i = 0; $i < 3; $i++) {
                    $this->binary[$i] = 0xffffffff;
                }
            }
        }

        if ($version == 4) {
            $mask    = decbin($this->binary[3]);
            $textual = [];
            for ($i = 0; $i < 4; $i++) {
                $textual[] = bindec(substr($mask, 8 * $i, 8));
            }
            $this->textual = implode(".", $textual);
        }
        return true;
    }
}
