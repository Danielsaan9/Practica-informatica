<?php

namespace Glpi\Search\Output;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageMargins;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

final class Pdf extends Spreadsheet
{
    public const PORTRAIT = PageSetup::ORIENTATION_PORTRAIT;
    public const LANDSCAPE = PageSetup::ORIENTATION_LANDSCAPE;

    public function __construct(string $orientation = self::PORTRAIT)
    {
        parent::__construct();

        $style = $this->spread->getDefaultStyle();

        $borders = $style->getBorders();
        $borders->getBottom()->setBorderStyle(Border::BORDER_DOTTED);

        // TCPDF 7 honors the spreadsheet default bottom vertical alignment
        $style->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        $pagesetup = $this->spread->getActiveSheet()->getPageSetup();
        $pagesetup->setPaperSize(PageSetup::PAPERSIZE_A4);
        $pagesetup->setRowsToRepeatAtTop([1, 1]); //OK, but align gap on 2nd page between header and body*/

        $margin = PageMargins::fromCentimeters(1);
        $this->spread->getActiveSheet()->getPageMargins()
            ->setTop($margin)
            ->setRight($margin)
            ->setLeft($margin);

        IOFactory::registerWriter('GLPIPdf', Tcpdf::class);
        /** @var \PhpOffice\PhpSpreadsheet\Writer\Pdf $writer */
        $writer = IOFactory::createWriter($this->spread, 'GLPIPdf');
        $writer->setOrientation($orientation);
        $this->writer = $writer;
    }

    public function getMime(): string
    {
        return 'appplication/pdf';
    }

    public function getFileName(): string
    {
        return "glpi.pdf";
    }
}
