<?php

namespace Glpi\Form\Export\Specification;

final class AccesControlPolicyContentSpecification implements ContentSpecificationInterface
{
    public string $strategy;
    public array $config;
    public bool $is_active;
}
