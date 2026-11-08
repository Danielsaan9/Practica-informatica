<?php

namespace Glpi\Toolbox;

use Toolbox;

/**
 * Helper class to build markdown content
 */
class MarkdownBuilder
{
    /**
     * Generated markdown content
     *
     */
    protected string $content = "";

    /**
     * Get the generated markdown content
     *
     * @return string
     */
    public function getMarkdown(): string
    {
        return $this->content;
    }

    /**
     * Add a header to the markdown content
     *
     * @param string $prefix Header type (#, ##, ...)
     * @param string $content Header content
     * @param string|null $css_class Css class to add to this header
     *
     * @return void
     */
    protected function addHeader(
        string $prefix,
        string $content,
        ?string $css_class = null
    ) {
        $css_class = !is_null($css_class) ? "{.$css_class}" : "";
        $this->content .= sprintf("%s %s %s \n", $prefix, $content, $css_class);
    }

    /**
     * Add a h1 header
     *
     * @param string $content Header content
     * @param string|null $css_class Css class to add to this header
     *
     * @return void
     */
    public function addH1(string $content, ?string $css_class = null)
    {
        $this->addHeader("#", $content, $css_class);
    }

    /**
     * Add a h2 header
     *
     * @param string $content Header content
     * @param string|null $css_class Css class to add to this header
     *
     * @return void
     */
    public function addH2(string $content, ?string $css_class = null)
    {
        $this->addHeader("##", $content, $css_class);
    }

    /**
     * Add a h3 header
     *
     * @param string $content Header content
     * @param string|null $css_class Css class to add to this header
     *
     * @return void
     */
    public function addH3(string $content, ?string $css_class = null)
    {
        $this->addHeader("###", $content, $css_class);
    }

    /**
     * Add a h4 header
     *
     * @param string $content Header content
     * @param string|null $css_class Css class to add to this header
     *
     * @return void
     */
    public function addH4(string $content, ?string $css_class = null)
    {
        $this->addHeader("####", $content, $css_class);
    }

    /**
     * Add a h5 header
     *
     * @param string $content Header content
     * @param string|null $css_class Css class to add to this header
     *
     * @return void
     */
    public function addH5(string $content, ?string $css_class = null)
    {
        $this->addHeader("#####", $content, $css_class);
    }

    /**
     * Add a h6 header
     *
     * @param string $content Header content
     * @param string|null $css_class Css class to add to this header
     *
     * @return void
     */
    public function addH6(string $content, ?string $css_class = null)
    {
        $this->addHeader("######", $content, $css_class);
    }

    /**
     * Add a table row
     *
     * @param array $values
     *
     * @return void
     */
    public function addTableRow(array $values)
    {
        $this->content .= "|" . implode("|", $values) . "\n";
    }

    /**
     * Add a table header
     *
     * @param array $headers
     *
     * @return void
     */
    public function addTableHeader(array $headers)
    {
        $separator = array_fill(0, count($headers), '------');
        $this->addTableRow($headers);
        $this->addTableRow($separator);
    }

    /**
     * Helper function to encapsulate single line code
     *
     * @param string $code
     *
     * @return string
     */
    public static function code($code): string
    {
        return sprintf("```%s```", str_replace('|', '\|', $code));
    }

    /**
     * Helper function create a navigation link
     *
     * @param string $label
     *
     * @return string
     */
    public static function navigationLink($label)
    {
        $link = Toolbox::slugify($label, '');
        return "[$label](#$link)";
    }

    /**
     * Helper function create a summary entry
     *
     * @param string $label
     *
     * @return void
     */
    public function addSummaryEntry($label)
    {
        $link = self::navigationLink($label);
        $this->content .= "* $link\n";
    }
}
