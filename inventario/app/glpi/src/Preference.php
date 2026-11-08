<?php

use Glpi\Security\SessionTracker;
use Glpi\Security\TOTPManager;

// class Preference for the current connected User
class Preference extends CommonGLPI
{
    #[Override]
    protected static function itemTypeRequiresReauthentication(): bool
    {
        return true;
    }

    public static function getTypeName($nb = 0)
    {
        // Always plural
        return __('Settings');
    }

    public function defineTabs($options = [])
    {

        $ong = [];
        $this->addStandardTab(User::class, $ong, $options);
        $this->addStandardTab(self::class, $ong, $options);
        if (Session::haveRightsOr(Profile::HELPDESK_RIGHT_PERSONALIZATION, [READ, UPDATE])) {
            $this->addStandardTab(Config::class, $ong, $options);
        }
        $this->addStandardTab(ValidatorSubstitute::class, $ong, $options);
        $this->addStandardTab(DisplayPreference::class, $ong, $options);

        $ong['no_all_tab'] = true;

        return $ong;
    }

    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        return [
            self::createTabEntry(text: __('Two-factor authentication (2FA)'), icon: 'ti ti-shield-lock'),
            self::createTabEntry(_n('Session', 'Sessions', Session::getPluralNumber()), 0, $item::class, 'ti ti-user-shield'),
        ];
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        if ($tabnum == 0) {
            $totp = new TOTPManager();
            $regenerate_backup_codes = isset($_REQUEST['regenerate_backup_codes']) ? filter_var($_REQUEST['regenerate_backup_codes'], FILTER_VALIDATE_BOOLEAN) : false;
            // Don't allow regenerating the codes from the URL if the user already has some to prevent malicious or accidental regenerations
            $regenerate_backup_codes = $regenerate_backup_codes && $totp->is2FAEnabled($_SESSION['glpiID']) && !$totp->isBackupCodesAvailable($_SESSION['glpiID']);
            $totp->showTOTPConfigForm($_SESSION['glpiID'], isset($_REQUEST['reset_2fa']), $regenerate_backup_codes);
        } elseif ($tabnum == 1) {
            (new SessionTracker())->showSessionList((int) Session::getLoginUserID());
        }
        return true;
    }

    /**
     * @FIXME Override the options inside the front controller.
     * @phpstan-ignore method.parentMethodFinalByPhpDoc (temporary solution to add the final tag)
     */
    public function showTabsContent($options = [])
    {
        if (isset($_REQUEST['reset_2fa'])) {
            $options['reset_2fa'] = $_REQUEST['reset_2fa'];
        }
        if (isset($_REQUEST['regenerate_backup_codes'])) {
            $options['regenerate_backup_codes'] = $_REQUEST['regenerate_backup_codes'];
        }
        parent::showTabsContent($options);
    }
}
