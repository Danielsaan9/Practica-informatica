<?php

namespace Glpi\System\Requirement;

/**
 * @since 10.0.0
 */
class DirectoriesWriteAccess extends AbstractRequirement
{
    /**
     * Directories paths.
     *
     * @var string[]
     */
    private array $paths;

    /**
     * @param string   $title     Requirement title.
     * @param string[] $paths     Directories paths.
     * @param bool     $optional  Indicated if write access is optional.
     */
    public function __construct(string $title, array $paths, bool $optional = false)
    {
        parent::__construct($title, null, $optional);

        $this->paths = $paths;
    }

    protected function check()
    {

        $this->validated = true;

        foreach ($this->paths as $path) {
            $directory_write_access = new DirectoryWriteAccess($path);
            $this->validated = $this->validated && $directory_write_access->isValidated();
            $this->validation_messages = array_merge($this->validation_messages, $directory_write_access->getValidationMessages());
        }
    }
}
