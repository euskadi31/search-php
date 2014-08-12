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

interface ResponseInterface
{
    public function count();

    public function offsetSet($offset, $value);

    public function offsetExists($offset);

    public function offsetUnset($offset);

    public function offsetGet($offset);

    public function toArray();

    public function keys();

    public function rewind();

    public function current();

    public function key();

    public function next();

    public function valid();
}
