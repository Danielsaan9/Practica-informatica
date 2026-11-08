<?php

namespace Glpi\DBAL;

use RuntimeException;

/**
 *  Query expression class
 **/
class QueryExpression implements QueryElementInterface, QueryAliasInterface
{
    use QueryAliasTrait;

    private string $expression;

    private ?string $alias;
    /** @var array<int, mixed> */
    private array $params = [];


    /**
     * Create a query expression
     *
     * @param string|QueryElementInterface $expression The query expression
     * @param ?string $alias     The query expression alias
     * @param array<int, mixed> $values    The query expression values
     */
    public function __construct(string|QueryElementInterface $expression, ?string $alias = null, array $values = [])
    {
        if ($expression === '') {
            throw new RuntimeException('Cannot build an empty expression');
        }
        $this->alias = $alias;

        if ($expression instanceof QueryElementInterface) {
            $this->expression = $expression->getValue();
            $values = array_merge($expression->getParams(), $values);
        } else {
            $this->expression = $expression;
        }
        $this->setParams($values);
    }

    /**
     * Query expression value
     *
     * @return string
     *
     * @psalm-taint-escape sql
     */
    public function getValue(): string
    {
        global $DB;
        $sql = $this->expression;
        if ($this->with_alias && !empty($this->alias)) {
            $sql .= ' AS ' . $DB::quoteName($this->alias);
        }
        return $sql;
    }

    public function __toString(): string
    {
        return $this->getValue();
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
