<?php

/**
 * @since 0.84
 **/
class HTMLTableSubHeader extends HTMLTableHeader implements HTMLCompositeTableInterface
{
    // The headers of each column
    /** @var HTMLTableSuperHeader The headers of each column */
    private HTMLTableSuperHeader $header;
    public int $numberOfSubHeaders;

    /**
     * @param HTMLTableSuperHeader $header
     * @param string               $name
     * @param string               $content
     * @param ?HTMLTableHeader     $father
     **/
    public function __construct(
        HTMLTableSuperHeader $header,
        $name,
        $content,
        ?HTMLTableHeader $father = null
    ) {

        $this->header = $header;
        parent::__construct($name, $content, $father);
        $this->copyAttributsFrom($this->header);
    }

    public function isSuperHeader()
    {
        return false;
    }

    public function getHeaderAndSubHeaderName(&$header_name, &$subheader_name)
    {
        $header_name    = $this->header->getName();
        $subheader_name = $this->getName();
    }

    #[Override]
    public function getCompositeName(): string
    {
        return $this->header->getCompositeName() . $this->getName();
    }

    protected function getTable()
    {
        return $this->header->getTable();
    }

    /**
     * @return HTMLTableSuperHeader
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param int $numberOfSubHeaders
     *
     * @return void
     */
    public function updateColSpan($numberOfSubHeaders)
    {
        $this->setColSpan($this->header->getColSpan() / $numberOfSubHeaders);
    }
}
