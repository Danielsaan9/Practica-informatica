<?php

namespace Glpi\Form\QuestionType;

use Glpi\DBAL\JsonFieldInterface;
use Override;

final class QuestionTypeActorsDefaultValueConfig implements JsonFieldInterface
{
    // Unique reference to hardcoded name used for serialization
    public const KEY_USERS_IDS     = "users_ids";
    public const KEY_GROUPS_IDS    = "groups_ids";
    public const KEY_SUPPLIERS_IDS = "suppliers_ids";

    public function __construct(
        private array $users_ids = [],
        private array $groups_ids = [],
        private array $suppliers_ids = [],
    ) {}

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        return new self(
            users_ids: $data[self::KEY_USERS_IDS] ?? [],
            groups_ids: $data[self::KEY_GROUPS_IDS] ?? [],
            suppliers_ids: $data[self::KEY_SUPPLIERS_IDS] ?? [],
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::KEY_USERS_IDS     => $this->users_ids,
            self::KEY_GROUPS_IDS    => $this->groups_ids,
            self::KEY_SUPPLIERS_IDS => $this->suppliers_ids,
        ];
    }

    public function getUsersIds(): array
    {
        return $this->users_ids;
    }

    public function getGroupsIds(): array
    {
        return $this->groups_ids;
    }

    public function getSuppliersIds(): array
    {
        return $this->suppliers_ids;
    }
}
