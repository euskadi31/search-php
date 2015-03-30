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
class RequestEvent extends Event
{
    /**
     * @var string
     */
    private $sql;

    /**
     * @var bool
     */
    private $cached;

    /**
     *
     * @param string $index
     * @param bool $cached
     */
    public function __construct($sql, $cached = false)
    {
        $this->sql      = $sql;
        $this->cached   = $cached;
    }

    /**
     * Set sql
     *
     * @param string $sql
     * @return RequestEvent
     */
    public function setSql($sql)
    {
        $this->sql = $sql;

        return $this;
    }

    /**
     * Get sql
     *
     * @return string
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * Set if cached
     *
     * @param bool $cached
     * @return RequestEvent
     */
    public function setCached($cached)
    {
        $this->cached = (bool) $cached;

        return $this;
    }

    /**
     * Get if cached
     *
     * @return bool
     */
    public function isCached()
    {
        return (bool) $this->cached;
    }
}
