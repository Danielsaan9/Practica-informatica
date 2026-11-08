<?php

namespace Glpi\Plugin;

use LogicException;

class HookManager
{
    protected string $plugin;

    public function __construct(string $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Add a given javascript file
     *
     * @param string $file
     */
    public function registerJavascriptFile(string $file): void
    {
        $this->registerFile(Hooks::ADD_JAVASCRIPT, $file);
    }

    /**
     * Add a given CSS file
     *
     * @param string $file
     */
    public function registerCSSFile(string $file): void
    {
        $this->registerFile(Hooks::ADD_CSS, $file);
    }

    /**
     * Add a given file for the given hook
     *
     * @param string $hook
     * @param string $file
     */
    protected function registerFile(string $hook, string $file): void
    {
        global $PLUGIN_HOOKS;

        // Check if the given hook is a valid file hook
        $allowed_file_hooks = Hooks::getFileHooks();
        if (!in_array($hook, $allowed_file_hooks)) {
            throw new LogicException(sprintf('Invalid file hook `%s`.', $hook));
        }

        // Init target array if needed
        if (!isset($PLUGIN_HOOKS[$hook][$this->plugin])) {
            $PLUGIN_HOOKS[$hook][$this->plugin] = [];
        }

        // Register file
        $PLUGIN_HOOKS[$hook][$this->plugin][] = $file;
    }

    /**
     * Add a functional hook
     *
     * @param string $hook
     * @param callable $function
     */
    public function registerFunctionalHook(
        string $hook,
        callable $function
    ): void {
        global $PLUGIN_HOOKS;

        // Check if the given hook is a valid functional hook
        $allowed_file_hooks = Hooks::getFunctionalHooks();
        if (!in_array($hook, $allowed_file_hooks)) {
            throw new LogicException(sprintf('Invalid functional hook `%s`.', $hook));
        }

        $PLUGIN_HOOKS[$hook][$this->plugin] = $function;
    }

    /**
     * Add an item hook
     *
     * @param string $hook
     * @param string $itemtype
     * @param callable $function
     */
    public function registerItemHook(
        string $hook,
        string $itemtype,
        callable $function
    ): void {
        global $PLUGIN_HOOKS;

        // Check if the given hook is a valid item hook
        $allowed_file_hooks = Hooks::getItemHooks();
        if (!in_array($hook, $allowed_file_hooks)) {
            throw new LogicException(sprintf('Invalid item hook `%s`.', $hook));
        }

        $PLUGIN_HOOKS[$hook][$this->plugin][$itemtype] = $function;
    }

    /**
     * Register fields that need to be encrypted
     *
     * @param array $fields array of table.field
     */
    public function registerSecureFields(array $fields): void
    {
        global $PLUGIN_HOOKS;

        $PLUGIN_HOOKS[Hooks::SECURED_FIELDS][$this->plugin] = $fields;
    }

    /**
     * Register configuration values that need to be encrypted
     *
     * @param array $configs
     */
    public function registerSecureConfigs(array $configs): void
    {
        global $PLUGIN_HOOKS;

        $PLUGIN_HOOKS[Hooks::SECURED_CONFIGS][$this->plugin] = $configs;
    }
}
