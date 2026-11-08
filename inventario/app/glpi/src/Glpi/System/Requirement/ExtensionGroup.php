<?php

namespace Glpi\System\Requirement;

/**
 * @since 10.0.0
 */
class ExtensionGroup extends AbstractRequirement
{
    /**
     * Required extensions names.
     *
     * @var string[]
     */
    protected array $extensions;

    /**
     * @param string      $title        Extension group title.
     * @param string[]    $extensions   Required extensions names.
     * @param bool        $optional     Indicate if extension is optional.
     * @param string|null $description  Describe usage of the extension.
     */
    public function __construct(string $title, array $extensions, bool $optional = false, ?string $description = null)
    {
        parent::__construct(
            $title,
            $description,
            $optional
        );

        $this->extensions = $extensions;
    }

    protected function check()
    {
        $loaded_extensions  = [];
        $missing_extensions = [];

        foreach ($this->extensions as $extension) {
            if (extension_loaded($extension)) {
                $loaded_extensions[] = $extension;
            } else {
                $missing_extensions[] = $extension;
            }
        }

        $this->validated = count($missing_extensions) === 0;

        if (count($loaded_extensions) > 0) {
            $this->validation_messages[] = sprintf(__('Following extensions are installed: %s.'), implode(', ', $loaded_extensions));
        }
        if (count($missing_extensions) > 0) {
            if ($this->optional) {
                $this->validation_messages[] = sprintf(__('Following extensions are not present: %s.'), implode(', ', $missing_extensions));
            } else {
                $this->validation_messages[] = sprintf(__('Following extensions are missing: %s.'), implode(', ', $missing_extensions));
            }
        }
    }
}
