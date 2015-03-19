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
class DeleteEvent extends Event
{
    /**
     * @var string
     */
    private $index;

    /**
     * @var integer
     */
    private $id;

    /**
     *
     * @param string $index
     * @param array $id
     */
    public function __construct($index, $id)
    {
        $this->index    = $index;
        $this->id       = $id;
    }

    /**
     * Set index
     *
     * @param string $index
     * @return DeleteEvent
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
     * Set id
     *
     * @param array $id
     * @return DeleteEvent
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
