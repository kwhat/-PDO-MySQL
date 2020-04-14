<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\MySQL\Statement;

use FaaPz\PDO\MySQL\Clause\Limit;
use FaaPz\PDO\QueryInterface;
use FaaPz\PDO\Statement;
use PDOStatement;

/**
 * @method PDOStatement execute()
 */
class Select extends Statement\Select
{
    /** @var Limit|null $limit */
    protected $limit = null;

    /**
     * @param Limit|null $limit
     *
     * @return $this
     */
    public function limit(?Limit $limit = null)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    public function getValues(): array
    {
        $values = parent::getValues();
        if ($this->limit != null) {
            $values = array_merge($values, $this->limit->getValues());
        }

        return $values;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if (empty($this->table)) {
            trigger_error('No table set for select statement', E_USER_ERROR);
        }

        $sql = 'SELECT';
        if ($this->distinct) {
            $sql .= ' DISTINCT';
        }

        $sql .= " {$this->getColumns()}";

        if (is_array($this->table)) {
            $table = reset($this->table);
            if ($table instanceof QueryInterface) {
                $table = "({$table})";
            }

            $alias = key($this->table);
            if (is_string($alias)) {
                $table .= " AS {$alias}";
            }
        } else {
            $table = "{$this->table}";
        }
        $sql .= " FROM {$table}";

        if (!empty($this->join)) {
            $sql .= ' ' . implode(' ', $this->join);
        }

        if ($this->where != null) {
            $sql .= " WHERE {$this->where}";
        }

        if (!empty($this->groupBy)) {
            $sql .= ' GROUP BY ' . implode(', ', $this->groupBy);
        }

        if ($this->having != null) {
            $sql .= " HAVING {$this->having}";
        }

        if ($direction = reset($this->orderBy)) {
            $column = key($this->orderBy);
            $sql .= " ORDER BY {$column} {$direction}";

            while ($direction = next($this->orderBy)) {
                $column = key($this->orderBy);
                $sql .= ", {$column} {$direction}";
            }
        }

        if ($this->limit != null) {
            $sql .= " {$this->limit}";
        }

        for ($i = 0; $i < $this->getUnionCount(); $i++) {
            if (isset($this->union[$i])) {
                $union = "({$this->union[$i]})";
            } else {
                $union = "ALL ({$this->unionAll[$i]})";
            }
            $sql = "({$sql}) UNION {$union}";
        }

        return $sql;
    }
}
