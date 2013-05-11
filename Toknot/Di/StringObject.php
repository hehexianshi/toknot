<?php

/**
 * Toknot (http://toknot.com)
 *
 * @copyright  Copyright (c) 2011 - 2013 Toknot.com
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @link       https://github.com/chopins/toknot
 */

namespace Toknot\Di;

use Toknot\Di\Object;
use \ReflectionFunction;
use \BadMethodCallException;
use \ReflectionExtension;
use \ArrayAccess;

class StringObject extends Object implements ArrayAccess {

    protected $interatorArray = '';
    private $walkIndex = 0;

    public function __construct($string) {
        $this->interatorArray = $string;
    }

    public static function hasMethod($name) {
        return in_array($name, self::supportStringMethod());
    }

    public static function supportStringMethod() {
        $ref = new ReflectionExtension('standard');
        $functionList = $ref->getFunctions();
        $supprot = array();
        foreach ($functionList as $funcRef) {
            if ($funcRef->getNumberOfRequiredParameters() < 1) {
                continue;
            }
            $parameters = $funcRef->getParameters();

            if (strpos($funcRef->name, 'stream') === 0) {
                continue;
            }
            if (strpos($funcRef->name, 'str') !== 0 && $parameters[0]->name != 'str' && $parameters[0]->name != 'string') {
                continue;
            }
            if ($parameters[0]->isPassedByReference()) {
                continue;
            }
            $supprot[] = $funcRef->name;
        }
        return $supprot;
    }

    public function __call($stringFunction, $arguments) {
        if (!function_exists($stringFunction)) {
            throw new BadMethodCallException("$stringFunction Method undefined in StringObject");
        }
        $stringFunctionReflection = new ReflectionFunction($stringFunction);
        if ($stringFunctionReflection->getExtensionName() != 'standard') {
            throw new BadMethodCallException("$stringFunction Method undefined in StringObject");
        }
        if ($stringFunctionReflection->getNumberOfRequiredParameters() < 1) {
            throw new BadMethodCallException("$stringFunction Method undefined in StringObject");
        }
        $parameters = $stringFunctionReflection->getParameters();
        if ($parameters[0]->name != 'str' && strpos($stringFunction, 'str') !== 0 && $parameters[0]->name != 'string') {
            throw new BadMethodCallException("$stringFunction Method undefined in StringObject");
        }
        if ($parameters[0]->isPassedByReference()) {
            throw new BadMethodCallException("$stringFunction Method undefined in StringObject");
        }
        array_unshift($arguments, $this->interatorArray);
        $str = $stringFunctionReflection->invokeArgs($arguments);
        if (is_string($str)) {
            return new StringObject($str);
        } else {
            return $str;
        }
    }

    public function __toString() {
        return $this->interatorArray;
    }

    public function count() {
        return strlen($this->interatorArray);
    }

    public function strlen() {
        return strlen($this->interatorArray);
    }

    public function rewind() {
        $this->walkIndex = 0;
    }

    public function current() {
        return $this->interatorArray[$this->walkIndex];
    }

    public function key() {
        return $this->walkIndex;
    }

    public function next() {
        $this->walkIndex++;
    }

    public function valid() {
        $key = $this->key();
        return isset($this->interatorArray[$key]);
    }

    public function offsetExists($offset) {
        return isset($this->interatorArray[$offset]);
    }

    public function offsetGet($offset) {
        return $this->interatorArray[$offset];
    }

    public function offsetSet($offset, $value) {
        if ($offset >= count($offset)) {
            $this->interatorArray .= $value;
        } else {
            $this->interatorArray = substr_replace($this->interatorArray, $value, $offset);
        }
    }

    public function offsetUnset($offset) {
        $this->interatorArray = substr_replace($this->interatorArray, '', $offset, 1);
    }

}