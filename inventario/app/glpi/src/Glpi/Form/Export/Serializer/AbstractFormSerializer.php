<?php

namespace Glpi\Form\Export\Serializer;

use Glpi\Form\Export\Specification\ExportContentSpecification;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Simple wrapper for the Serializer component.
 * This is where we configure options relative to symfony's serializer.
 *
 * Form serializing logic must be delegated to a concrete class that will extend
 * this one.
 */
abstract class AbstractFormSerializer
{
    private Serializer $serializer;

    public function __construct()
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [
            // Need to handle arrays of objects
            new ArrayDenormalizer(),

            // Fix union type handling of illustration field
            new CustomIllustrationContentSpecificationNormalizer(),

            // The `propertyTypeExtractor` parameter is required to normalize
            // nested objects because we are not a full symfony application.
            // See: https://symfony.com/doc/current/components/serializer.html#recursive-denormalization-and-type-safety
            new PropertyNormalizer(propertyTypeExtractor: new PhpDocExtractor()),
        ];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    protected function serialize(ExportContentSpecification $specification): string
    {
        return $this->serializer->serialize($specification, 'json', [
            PropertyNormalizer::NORMALIZE_VISIBILITY => PropertyNormalizer::NORMALIZE_PUBLIC,
        ]);
    }

    protected function deserialize(string $json): ExportContentSpecification
    {
        return $this->serializer->deserialize(
            $json,
            ExportContentSpecification::class,
            'json'
        );
    }
}
