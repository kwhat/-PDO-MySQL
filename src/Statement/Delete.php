<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\MySQL\Statement;

use FaaPz\PDO\MySQL\Clause\Limit;
use FaaPz\PDO\Statement;

class Delete extends Statement\Delete
{
    /** @var Limit|null $limit */
    protected $limit = null;

    /**
     * @param Limit|null $limit
     *
     * @return $this
     */
    public function limit(?Limit $limit)
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
        $sql = parent::__toString();
        if ($this->limit !== null) {
            $sql .= " {$this->limit}";
        }

        return $sql;
    }
}
