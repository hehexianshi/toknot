<?php

/**
* Toknot (http://toknot.com)
*
* @copyright Copyright (c) 2011 - 2013 Toknot.com
* @license http://opensource.org/licenses/bsd-license.php New BSD License
* @link https://github.com/chopins/toknot
*/

namespace Toknot\Db;

use Toknot\Db\DbTableObject;
use Toknot\Di\Object;
use Toknot\Exception\StandardException;

class DbTableColumn extends Object{
    private $columnName = null;
    private $tableObject = null;
    public $alias = null;
    public $type = null;
    public $length = 0;
    public $isPK = false;
    public $autoIncrement = false;
    public $value = '';
    public function __construct($columnName, DbTableObject &$tableObject) {
        $this->columnName = $columnName;
        $this->tableObject = $tableObject;
    }
    public function __get($name) {
        if(isset($this->$name)) {
            return $this->$name;
        }
        throw new StandardException("bad call property of {$this->columnName}::{$name}");
    }

    public function __toString() {
        $this->value = (string) $this->value;
        return $this->value;
    }
}