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
namespace Search\Tests\Units\Event;

use Search;

class RequestEvent extends Search\Test\Unit
{
    public function testConstructor()
    {
        $event = new Search\Event\RequestEvent('SELECT * FROM users');

        $this->object($event)->isInstanceOf('\Symfony\Component\EventDispatcher\Event');

        $this->string($event->getSql())->isEqualTo('SELECT * FROM users');
        $this->boolean($event->isCached())->isFalse();
    }

    public function testSql()
    {
        $event = new Search\Event\RequestEvent('SELECT * FROM users');

        $event->setSql('SELECT * FROM users WHERE status = 1');

        $this->string($event->getSql())->isEqualTo('SELECT * FROM users WHERE status = 1');
    }

    public function testCached()
    {
        $event = new Search\Event\RequestEvent('SELECT * FROM users', true);

        $this->boolean($event->isCached())->isTrue();

        $event->setCached(false);

        $this->boolean($event->isCached())->isFalse();
    }
}
