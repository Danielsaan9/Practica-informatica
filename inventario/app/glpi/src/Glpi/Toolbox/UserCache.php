<?php

namespace Glpi\Toolbox;

use User;

final class UserCache
{
    /** @param array<User|null> $users */
    public function __construct(
        private array $users = [],
    ) {}

    public function getUser(int $id): ?User
    {
        if (!array_key_exists($id, $this->users)) {
            $this->users[$id] = User::getById($id) ?: null;
        }

        return $this->users[$id];
    }
}
