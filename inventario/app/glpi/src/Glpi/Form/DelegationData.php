<?php

namespace Glpi\Form;

final class DelegationData
{
    public function __construct(
        public readonly ?int $users_id = null,
        public readonly ?bool $use_notification = null,
        public readonly ?string $alternative_email = null
    ) {}
}
