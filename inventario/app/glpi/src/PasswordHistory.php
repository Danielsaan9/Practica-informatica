<?php

/**
 * Helper class that contains method to handle password history validation or
 * change
 *
 * Implemented temporarily as a singleton, should become some kind of service
 * once GLPI support depencency injection.
 */
final class PasswordHistory
{
    /**
     * Max allowed history size in the password_history field
     */
    public const MAX_HISTORY_SIZE = 4;

    /**
     * Singleton instance
     */
    private static ?PasswordHistory $instance = null;

    /**
     * Get singleton instance
     *
     * @return PasswordHistory
     */
    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Validate that the user password was not used previously
     *
     * @param User $user
     * @param string $password
     *
     * @return bool
     */
    public function validatePassword(
        User $user,
        string $password
    ): bool {
        // Check if user is being created
        if ($user->isNewItem()) {
            // No history to handle for a new user, no need to go further
            return true;
        }

        // Check current password
        if (Auth::checkPassword($password, $user->fields['password'])) {
            return false;
        }

        // Check if password history is enabled
        $length = $this->getPasswordHistoryLengthToValidate();
        if ($length == 0) {
            // No password history, no need to go further
            return true;
        }

        $passwords_checked_count = 0;
        foreach ($this->getForUser($user) as $previous_password) {
            if (Auth::checkPassword($password, $previous_password)) {
                // New password match an entry in the history
                return false;
            }

            // History might contain more entries than configured (skip them)
            $passwords_checked_count++;
            if ($passwords_checked_count == $length) {
                break;
            }
        }

        // New password didn't match any entry in the history
        return true;
    }

    /**
     * Update password history for a given user
     * Must be called after a user password is updated
     *
     * @param User   $user
     * @param string|null $password Previous user's password, which has just been replaced
     *
     * @return bool
     */
    public function updatePasswordHistory(User $user, ?string $password): bool
    {
        if (empty($password)) {
            // There is no previous password to store. This is a normal case if a user is created without setting the password.
            // Login attempts with an empty password are not accepted by Auth::validateLogin()
            return true;
        }

        // Here $password should always be a hash and not a "real" password
        // To be extra safe and make sure we don't log any real password we can
        // verify that the hash algorithm is known
        if (password_get_info($password)['algoName'] == 'unknown') {
            trigger_error("Unhashed password has not been added to passwords history.", E_USER_WARNING);
            return false;
        }

        // Get full history
        $history = $this->getForUser($user);

        // Add hash to history
        array_unshift($history, $password);

        // Clear extra values in history
        $history = array_slice($history, 0, self::MAX_HISTORY_SIZE);

        // Save to DB
        $update = $user->update([
            'id' => $user->getId(),
            'password_history' => exportArrayToDB($history),
        ]);

        return $update;
    }

    /**
     * Get the password history of a given user
     *
     * @param User $user
     *
     * @return array
     */
    private function getForUser(User $user): array
    {
        if (empty($user->fields['password_history'])) {
            return [];
        }

        return importArrayFromDB($user->fields['password_history']);
    }

    /**
     * Singleton constructor
     */
    private function __construct() {}

    /**
     * Get the number of passwords stored in glpi_users.password_history that
     * must be verified according to the configuration
     *
     * $CFG_GLPI['non_reusable_passwords_count'] indicate the total number of
     * passwords that must be checked, which include glpi_users.password_history
     * (previous passwords) AND glpi_users.password (current password)
     *
     * This means the number of password to be checked in glpi_users.password_history
     * is always $CFG_GLPI['non_reusable_passwords_count'] - 1
     *
     * @return int
     */
    private function getPasswordHistoryLengthToValidate(): int
    {
        global $CFG_GLPI;
        $password_count = (int) ($CFG_GLPI['non_reusable_passwords_count'] ?? 1);
        return max(0, $password_count - 1);
    }
}
