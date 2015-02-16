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
    /**
     * Add new integer values set filter
     *
     * @param integer   $key   An attribute name.
     * @param array     $value Plain array of integer values.
     */
    public function setFilter($key, $value);

    /**
     * Add new integer range filter
     *
     * @param string    $key An attribute name.
     * @param integer   $min Minimum value.
     * @param integer   $max Maximum value.
     */
    public function setFilterRange($key, $min, $max);

    /**
     *
     * @param string    $key_lat    Name of a latitude attribute.
     * @param string    $key_long   Name of a longitude attribute.
     * @param float     $latitude   Anchor latitude in radians.
     * @param float     $longitude  Anchor longitude in radians.
     * @param integer   $distance   Distance in meters.
     */
    public function setGeoFilter($key_lat, $key_long, $latitude, $longitude, $distance);

    /**
     * Execute search query
     *
     * @param  string $term   Query string.
     * @param  string $index  An index name (or names).
     * @param  array  $fields
     * @return Search\ResponseInterface
     */
    public function search($term, $index, array $fields = array());
}
