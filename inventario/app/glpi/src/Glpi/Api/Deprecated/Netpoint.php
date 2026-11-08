<?php

namespace Glpi\Api\Deprecated;

use Glpi\Socket;

/**
 * @since 10.0.0
 */
class Netpoint implements DeprecatedInterface
{
    use CommonDeprecatedTrait;

    public function getType(): string
    {
        return Socket::class;
    }

    public function mapCurrentToDeprecatedHateoas(array $hateoas): array
    {
        $hateoas = $this->replaceCurrentHateoasRefByDeprecated($hateoas);
        return $hateoas;
    }

    public function mapDeprecatedToCurrentFields(object $fields): object
    {
        return $fields;
    }

    public function mapCurrentToDeprecatedFields(array $fields): array
    {
        return $fields;
    }

    public function mapDeprecatedToCurrentCriteria(array $criteria): array
    {
        return $criteria;
    }

    public function mapCurrentToDeprecatedSearchOptions(array $soptions): array
    {
        return $soptions;
    }
}
