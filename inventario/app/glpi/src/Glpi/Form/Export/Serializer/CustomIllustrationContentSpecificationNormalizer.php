<?php

namespace Glpi\Form\Export\Serializer;

use Glpi\Form\Export\Specification\CustomIllustrationContentSpecification;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class CustomIllustrationContentSpecificationNormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): CustomIllustrationContentSpecification
    {
        if (!\is_array($data)) {
            throw NotNormalizableValueException::createForUnexpectedDataType(
                'Expected array data for CustomIllustrationContentSpecification.',
                $data,
                ['array'],
                $context['deserialization_path'] ?? null,
            );
        }

        $specification = new CustomIllustrationContentSpecification();
        $specification->key = $data['key'];
        $specification->data = $data['data'];
        $specification->checksum = $data['checksum'];

        return $specification;
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === CustomIllustrationContentSpecification::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            CustomIllustrationContentSpecification::class => true,
        ];
    }
}
