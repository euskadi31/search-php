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

class ResponseEvent extends Search\Test\Unit
{
    public function testConstructor()
    {
        $response = new \mock\Search\ResponseInterface();

        $event = new Search\Event\ResponseEvent('users', 'kiefer', $response, 10.345);

        $this->object($event)->isInstanceOf('\Symfony\Component\EventDispatcher\Event');

        $this->string($event->getIndex())->isEqualTo('users');
        $this->string($event->getTerm())->isEqualTo('kiefer');
        $this->object($event->getResponse())->isEqualTo($response);
        $this->float($event->getTime())->isEqualTo(10.345);
    }

    public function testIndex()
    {
        $response = new \mock\Search\ResponseInterface();

        $event = new Search\Event\ResponseEvent('users', 'kiefer', $response, 10.345);

        $event->setIndex('foo');

        $this->string($event->getIndex())->isEqualTo('foo');
    }

    public function testTerm()
    {
        $response = new \mock\Search\ResponseInterface();

        $event = new Search\Event\ResponseEvent('users', 'kiefer', $response, 10.345);

        $event->setTerm('foo');

        $this->string($event->getTerm())->isEqualTo('foo');
    }

    public function testResponse()
    {
        $response = new \mock\Search\ResponseInterface();

        $event = new Search\Event\ResponseEvent('users', 'kiefer', $response, 10.345);

        $response2 = new \mock\Search\ResponseInterface();

        $event->setResponse($response2);

        $this->object($event->getResponse())->isEqualTo($response2);
    }

    public function testTime()
    {
        $response = new \mock\Search\ResponseInterface();

        $event = new Search\Event\ResponseEvent('users', 'kiefer', $response, 10.345);

        $event->setTime(11.06);

        $this->float($event->getTime())->isEqualTo(11.06);
    }
}
