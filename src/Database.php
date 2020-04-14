<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\MySQL;

use FaaPz\PDO;
use FaaPz\PDO\MySQL\Statement\Call;

class Database extends PDO\Database
{
    /**
     * @param PDO\Clause\Method|null $procedure
     *
     * @return Statement\Call
     */
    public function call(PDO\Clause\Method $procedure = null): Call
    {
        return new Statement\Call($this, $procedure);
    }

    /**
     * @param array<int|string, string> $columns
     *
     * @return Statement\Select
     */
    public function select(array $columns = ['*']): PDO\Statement\Select
    {
        return new Statement\Select($this, $columns);
    }

    /**
     * @param array<string, mixed> $pairs
     *
     * @return Statement\Update
     */
    public function update(array $pairs = []): PDO\Statement\Update
    {
        return new Statement\Update($this, $pairs);
    }

    /**
     * @param string|array<string, string> $table
     *
     * @return Statement\Delete
     */
    public function delete($table = null): PDO\Statement\Delete
    {
        return new Statement\Delete($this, $table);
    }
}
