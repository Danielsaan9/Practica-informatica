<?php

use Glpi\Application\View\TemplateRenderer;

/**
 *  Class used to manage LDAP replicate config
 */
class AuthLdapReplicate extends CommonDBTM
{
    public static string $rightname = 'config';

    public static function canCreate(): bool
    {
        return static::canUpdate();
    }

    public static function canPurge(): bool
    {
        return static::canUpdate();
    }

    public function getForbiddenStandardMassiveAction()
    {
        $forbidden   = parent::getForbiddenStandardMassiveAction();
        $forbidden[] = 'update';
        return $forbidden;
    }

    public function prepareInputForAdd($input)
    {
        return $this->prepareInput($input);
    }

    public function prepareInputForUpdate($input)
    {
        return $this->prepareInput($input);
    }

    /**
     * @param array<mixed> $input
     * @return array<mixed>|false
     */
    private function prepareInput(array $input): array|false
    {
        if (
            ($this->isNewItem() && (!isset($input['host']) || trim((string) $input['host']) === ''))
            || (!$this->isNewItem() && isset($input['host']) && trim((string) $input['host']) === '')
        ) {
            Session::addMessageAfterRedirect(
                htmlescape(
                    sprintf(
                        __('Mandatory fields are not filled. Please correct: %s'),
                        __('Server')
                    )
                ),
                false,
                ERROR
            );
            return false;
        }

        return $input;
    }

    /**
     * Form to add a replicate to a ldap server
     *
     * @param string  $target    target page for add new replicate
     * @param int $master_id master ldap server ID
     *
     * @return void
     */
    public static function addNewReplicateForm($target, $master_id)
    {
        TemplateRenderer::getInstance()->display('pages/setup/authentication/ldap_replicate.html.twig', [
            'target' => $target,
            'authldaps_id' => $master_id,
        ]);
    }
}
