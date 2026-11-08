<?php

namespace Glpi\Form\Tag;

/**
 * Simple data structure that will be json_encoded and sent to the
 * GLPI.RichText.FormTags component.
 */
final readonly class Tag
{
    public string $label;
    public string $html;

    public function __construct(
        string $label,
        string|int $value,
        TagProviderInterface $provider,
    ) {
        $this->label = $label;

        $color = $provider->getTagColor();

        // Build HTML representation of the tag.
        $properties = [
            "data-form-tag"          => "true",
            "data-form-tag-value"    => $value,
            "data-form-tag-provider" => $provider::class,
            "class"                  => "border-$color border-start border-3 bg-dark-lt",
        ];
        $properties = implode(" ", array_map(
            fn($key, $value) => sprintf('%s="%s"', htmlescape($key), htmlescape($value)),
            array_keys($properties),
            array_values($properties),
        ));
        $this->html = sprintf('<span %s>#%s</span>', $properties, htmlescape($label));
    }
}
