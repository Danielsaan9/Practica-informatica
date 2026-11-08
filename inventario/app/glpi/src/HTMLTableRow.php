<?php

/**
 * @since 0.84
 **/
class HTMLTableRow extends HTMLTableEntity
{
    private HTMLTableGroup $group;
    private bool $empty              = true;
    /** @var array<string, array<HTMLTableCell>> */
    private array $cells              = [];
    private int $numberOfSubRows    = 1;
    /** @var array<int, array> */
    private array $linesWithAttributs = [];

    /**
     * @param HTMLTableGroup $group
     */
    public function __construct($group)
    {
        $this->group = $group;
    }

    /**
     * @return HTMLTableGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return bool
     */
    public function notEmpty()
    {
        return !$this->empty;
    }

    /**
     * @return int
     */
    public function getNumberOfsubRows()
    {
        return $this->numberOfSubRows;
    }

    /**
     * @return HTMLTableRow
     */
    public function createAnotherRow()
    {
        return $this->group->createRow();
    }

    /**
     * @param int $lineIndex
     * @param array $attributs
     *
     * @return void
     */
    public function addAttributForLine($lineIndex, $attributs)
    {
        $this->linesWithAttributs[$lineIndex] = $attributs;
    }

    /**
     * @param HTMLTableHeader $header object
     * @param string|array $content
     * @param ?HTMLTableCell $father object (default NULL)
     * @param ?CommonDBTM $item object: The item associated with the current cell (default NULL)
     *
     * @return HTMLTableCell
     */
    public function addCell(
        HTMLTableHeader $header,
        $content,
        ?HTMLTableCell $father = null,
        ?CommonDBTM $item = null
    ) {

        if (!$this->group->haveHeader($header)) {
            throw new Exception('Unavailable header!');
        }

        if ($header instanceof HTMLCompositeTableInterface) {
            $header_name = $header->getCompositeName();
        } else {
            $header_name = $header->getName();
        }
        if (!isset($this->cells[$header_name])) {
            $this->cells[$header_name] = [];
        }

        $cell = new HTMLTableCell($this, $header, $content, $father, $item);
        $this->cells[$header_name][] = $cell;
        $this->empty = false;
        return $cell;
    }

    /**
     * @return bool
     */
    public function prepareDisplay()
    {
        if ($this->empty) {
            return false;
        }

        // First, compute the total nomber of rows ...
        $this->numberOfSubRows = 0;
        foreach ($this->cells as $cellsOfHeader) {
            if (isset($cellsOfHeader[0])) {
                $header = $cellsOfHeader[0]->getHeader();
                if (is_null($header->getFather())) {
                    $numberOfSubRowsPerHeader = 0;
                    foreach ($cellsOfHeader as $cell) {
                        $cell->computeNumberOfLines();
                        $numberOfSubRowsPerHeader += $cell->getNumberOfLines();
                    }
                    if ($this->numberOfSubRows < $numberOfSubRowsPerHeader) {
                        $this->numberOfSubRows = $numberOfSubRowsPerHeader;
                    }
                }
            }
        }

        // Then notify each cell and compute its starting row
        foreach ($this->cells as $cellsOfHeader) {
            if (isset($cellsOfHeader[0])) {
                $header = $cellsOfHeader[0]->getHeader();

                // Only do this for cells that don't have father: they will propagate this to there sons
                if (is_null($header->getFather())) {
                    HTMLTableCell::updateCellSteps($cellsOfHeader, $this->numberOfSubRows);

                    $start = 0;
                    foreach ($cellsOfHeader as $cell) {
                        $cell->computeStartEnd($start);
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param HTMLTableHeader[] $headers
     *
     * @return void
     */
    public function displayRow($headers)
    {
        echo "\t<tbody";
        $this->displayEntityAttributs();
        echo ">\n";
        for ($i = 0; $i < $this->numberOfSubRows; $i++) {
            if (isset($this->linesWithAttributs[$i])) {
                $options = $this->linesWithAttributs[$i];
            } else {
                $options = [];
            }
            echo "\t\t<tr class='tab_bg_1'>\n";
            foreach ($headers as $header) {
                if (!$header instanceof HTMLCompositeTableInterface) {
                    continue;
                }
                $header_name = $header->getCompositeName();
                if (isset($this->cells[$header_name])) {
                    $display = false;
                    foreach ($this->cells[$header_name] as $cell) {
                        $display |= $cell->displayCell($i, $options);
                    }
                    if (!$display) {
                        echo "\t\t\t<td colspan='" . ((int) $header->getColSpan()) . "'";
                        $header->displayEntityAttributs($options);
                        echo "></td>\n";
                    }
                } else {
                    echo "\t\t\t<td colspan='" . ((int) $header->getColSpan()) . "'";
                    $header->displayEntityAttributs($options);
                    echo "></td>\n";
                }
            }
            echo "\t\t</tr>\n";
        }
        echo "\t</tbody>\n";
    }

    /**
     * @param string $name
     * @param ?string $sub_name  (default NULL)
     *
     * @return HTMLTableHeader
     */
    public function getHeaderByName($name, $sub_name = null)
    {
        return $this->group->getHeaderByName($name, $sub_name);
    }
}
