<?php

namespace Glpi\Form\AccessControl\ControlType;

use Glpi\DBAL\JsonFieldInterface;
use Override;

final class AllowListConfig implements JsonFieldInterface
{
    // Serialized keys names
    public const KEY_USER_IDS = 'user_ids';
    public const KEY_GROUP_IDS = 'group_ids';
    public const KEY_PROFILE_IDS = 'profile_ids';

    public function __construct(
        private array $user_ids = [],
        private array $group_ids = [],
        private array $profile_ids = [],
    ) {}

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        return new self(
            user_ids   : $data[self::KEY_USER_IDS] ?? [],
            group_ids  : $data[self::KEY_GROUP_IDS] ?? [],
            profile_ids: $data[self::KEY_PROFILE_IDS] ?? []
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::KEY_USER_IDS    => $this->user_ids,
            self::KEY_GROUP_IDS   => $this->group_ids,
            self::KEY_PROFILE_IDS => $this->profile_ids,
        ];
    }

    public function getUserIds(): array
    {
        return $this->user_ids;
    }

    public function getGroupIds(): array
    {
        return $this->group_ids;
    }

    public function getProfileIds(): array
    {
        return $this->profile_ids;
    }
}
