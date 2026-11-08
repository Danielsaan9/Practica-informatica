<?php

namespace Glpi\Toolbox;

/**
 * Represents a file identified by a display name and its location on disk.
 */
final readonly class FileInfo
{
    public function __construct(
        private string $filename,
        private string $filepath,
    ) {}

    /**
     * Display/original name of the file.
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * Actual path of the file on disk.
     */
    public function getFilepath(): string
    {
        return $this->filepath;
    }
}
