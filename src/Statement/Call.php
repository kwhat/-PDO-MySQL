<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\MySQL\Statement;

use FaaPz\PDO\AbstractStatement;
use FaaPz\PDO\Clause\Method;
use PDO;
use PDOStatement;

/**
 * @method PDOStatement execute()
 */
class Call extends AbstractStatement
{
    /** @var Method|null $method */
    protected $method = null;

    /**
     * @param PDO         $dbh
     * @param Method|null $procedure
     */
    public function __construct(PDO $dbh, ?Method $procedure = null)
    {
        parent::__construct($dbh);

        if ($procedure != null) {
            $this->method($procedure);
        }
    }

    /**
     * @param Method $procedure
     *
     * @return $this
     */
    public function method(Method $procedure): self
    {
        $this->method = $procedure;

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getValues(): array
    {
        return $this->method->getValues();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->method == null) {
            trigger_error('No method set for call statement', E_USER_ERROR);
        }

        return "CALL {$this->method}";
    }
}
