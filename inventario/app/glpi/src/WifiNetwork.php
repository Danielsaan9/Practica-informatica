<?php

/// Class WifiNetwork
/// since version 0.84
class WifiNetwork extends CommonDropdown
{
    public bool $dohistory          = true;

    public static string $rightname          = 'internet';

    public bool $can_be_translated  = false;


    public static function getTypeName($nb = 0)
    {
        return _n('Wifi network', 'Wifi networks', $nb);
    }

    /**
     * @return array<string,string>
     */
    public static function getWifiCardVersion()
    {
        return [
            ''          => '',
            'a'         => 'a',
            'b'         => 'b',
            'g'         => 'g',
            'n'         => 'n',
            'a/b'       => 'a/b',
            'a/b/g'     => 'a/b/g',
            'a/b/g/n'   => 'a/b/g/n',
            'a/b/g/n/y' => 'a/b/g/n/y',
            'b/g/n'     => 'b/g/n',
            'g/n'       => 'g/n',
            'ac'        => 'ac', // Wifi 5
            'ad'        => 'ad',
            'af'        => 'af',
            'ah'        => 'ah',
            'ax'        => 'ax', // Wifi 6/6E
            'y'         => 'y', // 802.11a on 3,7 GHz - United States only
            'be'        => 'be', // Wifi 7
            'bn'        => 'bn', // Wifi 8
        ];
    }


    /**
     * @return array<string,string>
     */
    public static function getWifiCardModes()
    {

        return [
            ''          => Dropdown::EMPTY_VALUE,
            'ad-hoc'    => _x('wifi_card_mode', 'Ad-hoc'),
            'managed'   => _x('wifi_card_mode', 'Managed'),
            'master'    => _x('wifi_card_mode', 'Master'),
            'repeater'  => _x('wifi_card_mode', 'Repeater'),
            'secondary' => _x('wifi_card_mode', 'Secondary'),
            'monitor'   => _x('wifi_card_mode', 'Monitor'),
            'auto'      => _x('wifi_card_mode', 'Automatic'),
        ];
    }


    /**
     * @return array<string,string>
     */
    public static function getWifiNetworkModes()
    {

        return [''               => Dropdown::EMPTY_VALUE,
            'infrastructure' => __('Infrastructure (with access point)'),
            'ad-hoc'         => __('Ad-hoc (without access point)'),
        ];
    }


    public function defineTabs($options = [])
    {

        $ong  = [];
        $this->addDefaultFormTab($ong);
        $this->addStandardTab(NetworkPort::class, $ong, $options);

        return $ong;
    }


    public function getAdditionalFields()
    {

        return [['name'  => 'essid',
            'label' => __('ESSID'),
            'type'  => 'text',
            'list'  => true,
        ],
            ['name'  => 'mode',
                'label' => __('Wifi network type'),
                'type'  => 'wifi_mode',
                'list'  => true,
            ],
        ];
    }


    public function displaySpecificTypeField($ID, $field = [], array $options = [])
    {

        if ($field['type'] == 'wifi_mode') {
            Dropdown::showFromArray(
                $field['name'],
                self::getWifiNetworkModes(),
                [
                    'value' => $this->fields[$field['name']],
                    'width' => '100%',
                ]
            );
        }
    }


    public function rawSearchOptions()
    {
        $tab = parent::rawSearchOptions();

        $tab[] = [
            'id'                 => '10',
            'table'              => $this->getTable(),
            'field'              => 'essid',
            'name'               => __('ESSID'),
            'datatype'           => 'string',
        ];

        return $tab;
    }

    public static function getIcon()
    {
        return "ti ti-wifi";
    }
}
