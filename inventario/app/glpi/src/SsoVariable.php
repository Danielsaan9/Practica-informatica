<?php

/**
 * @since 0.84
 **/
class SsoVariable extends CommonDropdown
{
    public static string $rightname = 'config';

    public bool $can_be_translated = false;


    public static function getTypeName($nb = 0)
    {

        return _n(
            'Field storage of the login in the HTTP request',
            'Fields storage of the login in the HTTP request',
            $nb
        );
    }


    public static function canCreate(): bool
    {
        return static::canUpdate();
    }

    public static function canPurge(): bool
    {
        return static::canUpdate();
    }

    public function prepareInputForAdd($input)
    {
        $input = $this->prepareInput($input);

        if ($input === false) {
            return false;
        }

        return parent::prepareInputForAdd($input);
    }

    public function prepareInputForUpdate($input)
    {
        $input = $this->prepareInput($input);

        if ($input === false) {
            return false;
        }

        return parent::prepareInputForUpdate($input);
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>|false
     */
    private function prepareInput(array $input): array|false
    {
        if (
            $this->isServerVariableSafe($input['name'] ?? '') === false
            && ($this->isNewItem() || $input['name'] !== $this->fields['name'])
        ) {
            Session::addMessageAfterRedirect(
                sprintf(
                    __s('The %s variable is unsafe. It should not be used for authentication.'),
                    htmlescape($input['name']),
                ),
                false,
                ERROR
            );
            return false;
        }

        return $input;
    }


    public function cleanRelationData()
    {

        parent::cleanRelationData();

        if ($this->isUsedInAuth()) {
            $newval = ($this->input['_replace_by'] ?? 0);

            Config::setConfigurationValues(
                'core',
                [
                    'ssovariables_id' => $newval,
                ]
            );
        }
    }


    public function isUsed()
    {

        if (parent::isUsed()) {
            return true;
        }

        return $this->isUsedInAuth();
    }


    /**
     * Check if variable is used in auth process.
     *
     * @return bool
     */
    private function isUsedInAuth()
    {

        $config_values = Config::getConfigurationValues('core', ['ssovariables_id']);

        return array_key_exists('ssovariables_id', $config_values)
         && $config_values['ssovariables_id'] == $this->fields['id'];
    }

    /**
     * Indicates whether the current variable is safe.
     */
    public function isSafe(): bool
    {
        return $this->isServerVariableSafe($this->fields['name'] ?? '');
    }

    /**
     * Indicates whether a server variable is safe.
     */
    private function isServerVariableSafe(string $name): bool
    {
        $name = mb_strtoupper($name);

        return str_starts_with($name, 'HTTP_') === false
            && $name !== 'PHP_AUTH_USER';
    }
}
