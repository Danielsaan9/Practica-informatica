<?php

/**
 * Only an HTMLTableMain can create an HTMLTableSuperHeader.
 * @since 0.84
 **/
class HTMLTableSuperHeader extends HTMLTableHeader implements HTMLCompositeTableInterface
{
    /** @var HTMLTableBase The table that owns the current super header */
    private HTMLTableBase $table;

    /**
     * @param HTMLTableBase         $table    HTMLTableBase object: table owning the current header
     * @param string                $name     the name of the header
     * @param string                $content  see inc/HTMLTableEntity#__construct()
     * @param ?HTMLTableHeader      $father   HTMLTableHeader objet (default NULL)
     */
    public function __construct(HTMLTableBase $table, $name, $content, ?HTMLTableHeader $father = null)
    {
        $this->table = $table;
        parent::__construct($name, $content, $father);
    }

    /**
     * Compute the Least Common Multiple of two integers
     *
     * @param int $first
     * @param int $second
     *
     * @return int LCM of $first and $second
     */
    private static function LCM($first, $second)
    {
        $result = $first * $second;
        while ($first > 1) {
            $reste = $first % $second;
            if ($reste === 0) {
                $result /= $second;
                break;  // leave when LCM is found
            }
            $first = $second;
            $second = $reste;
        }
        return $result;
    }

    public function isSuperHeader()
    {
        return true;
    }

    public function getHeaderAndSubHeaderName(&$header_name, &$subheader_name)
    {

        $header_name    = $this->getName();
        $subheader_name = '';
    }

    #[Override]
    public function getCompositeName(): string
    {
        return $this->getName() . ':';
    }

    protected function getTable()
    {
        return $this->table;
    }

    /**
     * compute the total number of current super header colspan: it is the Least Common
     * Multiple of the colspan of each subHeader it owns.
     *
     * @param int $number the colspan for this header given by the group
     *
     * @return void
     */
    public function updateNumberOfSubHeader($number)
    {
        $this->setColSpan(self::LCM($number, $this->getColSpan()));
    }

    /**
     * The super headers always have to be displayed, conversely to sub headers
     *
     * @return true
     **/
    public function hasToDisplay()
    {
        return true;
    }
}
