<?php

namespace Glpi\Search\Output;

use GLPIPDF;

use function Safe\preg_replace;

class Tcpdf extends \PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf
{
    protected function createExternalWriterInstance($orientation, $unit, $paperSize): \TCPDF
    {
        $instance = new class (
            [
                'orientation' => $orientation,
                'unit' => $unit,
                'format' => $paperSize,
                'font_size' => 8,
                'font' => $_SESSION['glpipdffont'] ?? 'helvetica',
                'margin_bottom' => 30,
            ],
            $this->spreadsheet->getProperties()->getCustomPropertyValue('items count'),
            null,
            false
        ) extends GLPIPDF {
            /**
             * @param bool $val
             *
             * @return void
             */
            public function setPrintFooter($val = true)
            {
                //override because \PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf::save() explicitly calls setPrintFooter(false) -_-
                parent::setPrintFooter(true);
            }
        };

        //remove size considerations so TCPDF do its work.
        $callback = (function ($html) {
            $html = preg_replace(
                [
                    '|</style>|',
                    '|width:\d+pt"|',
                    '|padding-left:\dpx;|',
                ],
                [
                    'table { width: 100%; } th, td { padding: 1pt 2pt; }</style>',
                    '"',
                    '',
                ],
                $html
            );

            // TCPDF 7 sizes columns from their content, spread them evenly instead
            $columns_count = substr_count($html, '<col ');
            if ($columns_count > 0) {
                $html = preg_replace('|<td |', '<td width="' . round(100 / $columns_count, 2) . '%" ', $html);
            }

            return $html;
        });
        $this->setEditHtmlCallback($callback);

        return $instance;
    }
}
