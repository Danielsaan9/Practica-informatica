<?php

class OperatingSystemKernelVersion extends CommonDropdown
{
    public bool $can_be_translated = false;

    public static function getTypeName($nb = 0)
    {
        return _n('Kernel version', 'Kernel versions', $nb);
    }

    public function getAdditionalFields()
    {
        $fields   = parent::getAdditionalFields();
        $fields[] = [
            'label'  => OperatingSystemKernel::getTypeName(1),
            'name'   => OperatingSystemKernel::getTypeName(Session::getPluralNumber()),
            'list'   => true,
            'type'   => 'oskernel',
        ];

        return $fields;
    }

    public function displaySpecificTypeField($ID, $field = [], array $options = [])
    {
        switch ($field['type']) {
            case 'oskernel':
                OperatingSystemKernel::dropdown([
                    'value'     => $this->fields['operatingsystemkernels_id'],
                    'width'     => '100%',
                ]);
                break;
        }
    }
}
