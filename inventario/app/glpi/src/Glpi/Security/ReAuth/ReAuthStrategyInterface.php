<?php

declare(strict_types=1);

namespace Glpi\Security\ReAuth;

use Symfony\Component\HttpFoundation\Request;

interface ReAuthStrategyInterface
{
    /**
     * Verify the prompt form submission against this re-authentication strategy.
     *
     * The whole request is passed so a strategy is free to read as many fields (or headers)
     * as it needs: a single secret (the `user_input` field of the native prompt templates)
     * is only the simplest case.
     *
     * @param int     $users_id The user ID
     * @param Request $request  The prompt form submission
     * @return bool   Successful verification
     */
    public function verify(int $users_id, Request $request): bool;

    /**
     * Prompt form submission's url
     */
    public function getVerifyUrl(): string;

    /**
     * Prompt form's method
     *
     * @return 'POST'|'GET'
     */
    public function getVerifyHttpMethod(): string;

    /**
     * Check if this strategy is available for the given user.
     *
     * @param int $users_id The user ID
     * @return bool True if the strategy can be used for this user, false otherwise
     */
    public function isAvailable(int $users_id, int $entities_id = 0): bool;

    /**
     * Get a human-readable label for this strategy (used in UI).
     */
    public function getLabel(): string;

    /**
     * uri of a template displaying a reauth form
     *
     * e.g. '@myplugin/reauth/prompt.html.twig';
     */
    public function getPromptTemplate(): string;

    /**
     * Get the priority of this strategy.
     *
     * When multiple strategies are available for a user, the one with the HIGHEST priority is selected.
     *
     * @return int A positive integer. Higher values = higher priority
     */
    public function getPriority(): int;
}
