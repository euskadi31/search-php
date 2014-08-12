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
namespace Search\Engine\Sphinx;

use Search\AbstractResponse;

class Response extends AbstractResponse
{
    /**
     * general success, command-specific reply follows
     */
    const OK         = 0;

    /**
     * general failure, error message follows
     */
    const ERROR      = 1;

    /**
     * temporary failure, error message follows, client should retry later
     */
    const RETRY      = 2;

    /**
     * general success, warning message and command-specific reply follow
     */
    const WARNING    = 3;

    /**
     *
     * @var string
     */
    protected $error;

    /**
     *
     * @var string
     */
    protected $warning;

    /**
     *
     * @var integer
     */
    protected $status = 0;

    /**
     *
     * @param array $results Sphinx results
     */
    public function __construct($results)
    {
        $this->status = (int)$results['status'];

        $this->error = $results['error'];
        $this->warning = $results['warning'];

        $this->total = $results['total'];

        if (isset($results['matches'])) {
            $this->container = $results['matches'];
        }
    }

    /**
     *
     * @return boolean
     */
    public function isError()
    {
        return ($this->status === self::ERROR || $this->status === self::RETRY);
    }

    /**
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return ($this->status === self::OK || $this->status === self::WARNING);
    }

    /**
     *
     * @return boolean
     */
    public function isWarning()
    {
        return ($this->status === self::WARNING);
    }

    /**
     * Get error message
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get warning message
     *
     * @return string
     */
    public function getWarning()
    {
        return $this->warning;
    }

    /**
     * Get all messages
     *
     * @return string
     */
    public function getMessages()
    {
        $messages = array();

        if (!empty($this->error)) {
            $messages[] = $this->error;
        }

        if (!empty($this->warning)) {
            $messages[] = $this->warning;
        }

        return $messages;
    }
}
