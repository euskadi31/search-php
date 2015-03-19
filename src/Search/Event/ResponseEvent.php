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
use Search\ResponseInterface;

/**
 * Specific Event class for search
 */
class ResponseEvent extends Event
{
    /**
     * @var string
     */
    private $index;

    /**
     * @var string
     */
    private $term;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var float
     */
    private $time;

    /**
     *
     * @param string $index
     * @param string $term
     * @param ResponseInterface $response
     * @param float  $time
     */
    public function __construct($index, $term, ResponseInterface $response, $time = 0.0)
    {
        $this->index    = $index;
        $this->term     = $term;
        $this->response = $response;
        $this->time     = (float)$time;
    }

    /**
     * Set index
     *
     * @param string $index
     * @return ResponseEvent
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
     * Set term
     *
     * @param string $term
     * @return SearchEvent
     */
    public function setTerm($term)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * Get term
     *
     * @return string
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Set time
     *
     * @param float $time
     * @return SearchEvent
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return float
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set response
     *
     * @param ResponseInterface $response
     * @return SearchEvent
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
