<?php

use Glpi\DBAL\QueryElementInterface;

/**
 *  Sub query class
 **/
abstract class AbstractQuery implements QueryElementInterface
{
    protected ?string $alias = null;
    /** @var array<int, mixed> */
    protected array $params = [];

    /**
     * Create a query
     *
     * @param string $alias Alias for the whole subquery
     */
    public function __construct($alias = null)
    {
        $this->alias = $alias;
    }

    /**
     * Get alias
     *
     * @return string|null
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     *
     * Get SQL query
     *
     * @return string
     *
     * @psalm-taint-escape sql
     */
    abstract public function getQuery();

    /**
     * @psalm-taint-escape sql
     */
    public function getValue(): string
    {
        return $this->getQuery();
    }

    public function __toString(): string
    {
        return $this->getQuery();
    }

    /**
    * @return array<int, mixed>
    */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array<int, mixed> $params
     */
    public function setParams(array $params): static
    {
        $this->params = $params;
        return $this;
    }
}
