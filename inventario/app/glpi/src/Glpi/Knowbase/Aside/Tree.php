<?php

namespace Glpi\Knowbase\Aside;

final class Tree implements Node
{
    /** @var Article[] */
    protected array $articles = [];

    public function addArticle(Article $article): void
    {
        $this->articles[] = $article;
    }

    /** @return Article[] */
    public function getArticles(): array
    {
        return $this->articles;
    }
}
