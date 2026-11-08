<?php

namespace Glpi\Form\Export\Specification;

final class TranslationContentSpecification implements ContentSpecificationInterface
{
    public string $itemtype;
    public string $items_id;
    public string $key;
    public string $language;
    public array $translations;
}
