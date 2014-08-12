<?php
/**
 * @package     Search
 * @author      Axel Etcheverry <axel@etcheverry.biz>
 * @copyright   Copyright (c) 2014 Axel Etcheverry (https://twitter.com/euskadi31)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @namespace
 */
namespace Search;

use ArrayAccess;
use Countable;
use Iterator;

abstract class AbstractResponse implements Iterator, ArrayAccess, Countable, ResponseInterface
{
    /**
     *
     * @var array
     */
    protected $container = [];

    /**
     *
     * @var integer
     */
    protected $total = 0;

    /**
     *
     * @return int
     */
    public function count()
    {
        return $this->total;
    }

    /**
     *
     * @param  mixed $offset
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     *
     * @param  mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     *
     * @param  mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     *
     * @param  mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     *
     * @return array
     */
    public function toArray()
    {
        return $this->container;
    }

    /**
     * Return all the keys or a subset of the keys of an array
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->container);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void
     */
    public function rewind()
    {
        return reset($this->container);
    }

    /**
     * Return the current element
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->container);
    }

    /**
     * Return the key of the current element
     *
     * @return scalar
     */
    public function key()
    {
        return key($this->container);
    }

    /**
     * Move forward to next element
     *
     * @return void
     */
    public function next()
    {
        return next($this->container);
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean
     */
    public function valid()
    {
        return key($this->container) !== null;
    }
}
