<?php

namespace Glpi\Knowbase\Aside;

interface Node
{
    public function addArticle(Article $article): void;

    /** @return Article[] */
    public function getArticles(): array;
}
