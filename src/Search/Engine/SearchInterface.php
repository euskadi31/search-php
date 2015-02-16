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
namespace Search\Engine;

interface SearchInterface
{
    public function setFilter($key, $value);

    public function setFilterRange($key, $min, $max);

    public function search($term, $index, array $fields = array());
}
