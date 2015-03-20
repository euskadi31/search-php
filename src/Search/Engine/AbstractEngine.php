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

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ArrayCache;

abstract class AbstractEngine
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var integer
     */
    protected $cache_life_time = 0;


    /**
     * Set event dispatcher
     *
     * @param EventDispatcherInterface $dispatcher
     * @return AbstractEngine
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    /**
     * Get event dispatcher
     *
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        if (is_null($this->dispatcher)) {
            $this->dispatcher = new EventDispatcher;
        }

        return $this->dispatcher;
    }

    /**
     * Set cache driver
     *
     * @param Cache $cache
     * @return AbstractEngine
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Get cache driver
     *
     * @return Cache
     */
    public function getCache()
    {
        if (is_null($this->cache)) {
            $this->cache = new ArrayCache();
        }

        return $this->cache;
    }

    /**
     * Set Cache life time
     *
     * @param integer $time
     * @return AbstractEngine
     */
    public function setCacheLife($time)
    {
        $this->cache_life_time = (int) $time;

        return $this;
    }

    /**
     * Get cache life time
     *
     * @return integer
     */
    public function getCacheLife()
    {
        return $this->cache_life_time;
    }
}
