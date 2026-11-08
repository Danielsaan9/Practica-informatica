<?php

namespace Glpi\Search\Output;

use PhpOffice\PhpSpreadsheet\Writer\Ods\Mimetype;
use RuntimeException;

final class Ods extends Spreadsheet
{
    public function __construct()
    {
        parent::__construct();
        $this->writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($this->spread);
    }

    public function getMime(): string
    {
        // This can't happen but it helps with static analysis
        if (!$this->writer instanceof \PhpOffice\PhpSpreadsheet\Writer\Ods) {
            throw new RuntimeException();
        }

        $mime = new Mimetype($this->writer);
        return $mime->write();
    }

    public function getFileName(): string
    {
        return "glpi.ods";
    }
}
