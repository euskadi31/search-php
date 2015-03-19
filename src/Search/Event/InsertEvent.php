<?php
/**
 * @package     Search
 * @author      Axel Etcheverry <axel@etcheverry.biz>
 * @copyright   Copyright (c) 2014-2015 Axel Etcheverry (https://twitter.com/euskadi31)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @namespace
 */
namespace Search\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Specific Event class for search
 */
class InsertEvent extends Event
{
    /**
     * @var string
     */
    private $index;

    /**
     * @var array
     */
    private $data;

    /**
     *
     * @param string $index
     * @param array $data
     */
    public function __construct($index, array $data)
    {
        $this->index    = $index;
        $this->data     = $data;
    }

    /**
     * Set index
     *
     * @param string $index
     * @return InsertEvent
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Get index
     *
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set data
     *
     * @param array $data
     * @return InsertEvent
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
