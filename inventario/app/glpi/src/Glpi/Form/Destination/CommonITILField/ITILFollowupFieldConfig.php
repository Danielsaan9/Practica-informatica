<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Destination\ConfigFieldWithStrategiesInterface;
use Override;

final class ITILFollowupFieldConfig implements
    JsonFieldInterface,
    ConfigFieldWithStrategiesInterface
{
    // Unique reference to hardcoded names used for serialization and forms input names
    public const STRATEGY = 'strategy';
    public const ITILFOLLOWUPTEMPLATE_IDS = 'itilfollowuptemplate_ids';

    public function __construct(
        private ITILFollowupFieldStrategy $strategy,
        private ?array $specific_itilfollowuptemplates_ids = null,
    ) {}

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        $strategy = ITILFollowupFieldStrategy::tryFrom($data[self::STRATEGY] ?? "");
        if ($strategy === null) {
            $strategy = ITILFollowupFieldStrategy::NO_FOLLOWUP;
        }

        return new self(
            strategy: $strategy,
            specific_itilfollowuptemplates_ids: $data[self::ITILFOLLOWUPTEMPLATE_IDS] ?? [],
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::STRATEGY => $this->strategy->value,
            self::ITILFOLLOWUPTEMPLATE_IDS => $this->specific_itilfollowuptemplates_ids,
        ];
    }

    #[Override]
    public static function getStrategiesInputName(): string
    {
        return self::STRATEGY;
    }

    /**
     * @return array<ITILFollowupFieldStrategy>
     */
    public function getStrategies(): array
    {
        return [$this->strategy];
    }

    public function getSpecificITILFollowupTemplatesIds(): ?array
    {
        return $this->specific_itilfollowuptemplates_ids;
    }
}
