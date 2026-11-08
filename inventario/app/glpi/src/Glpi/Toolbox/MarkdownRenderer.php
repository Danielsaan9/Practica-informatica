<?php

namespace Glpi\Toolbox;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\MarkdownConverter;

class MarkdownRenderer
{
    private bool $with_headings = true;

    /**
     * Render markdown
     *
     * @param string $md_content Markdown to render
     * @return string
     * @throws CommonMarkException
     */
    public function render(string $md_content): string
    {
        // Define your configuration, if needed
        $config = [];

        // Configure the Environment with all the extensions you need
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        if ($this->with_headings) {
            $environment->addExtension(new HeadingPermalinkExtension());
            $environment->addExtension(new TableOfContentsExtension());
        }

        $converter = new MarkdownConverter($environment);
        return $converter->convert($md_content);
    }

    public function disableHeadings(): MarkdownRenderer
    {
        $this->with_headings = false;
        return $this;
    }
}
