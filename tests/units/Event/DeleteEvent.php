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

class DeleteEvent extends Search\Test\Unit
{
    public function testConstructor()
    {
        $event = new Search\Event\DeleteEvent('users', 123);

        $this->object($event)->isInstanceOf('\Symfony\Component\EventDispatcher\Event');

        $this->string($event->getIndex())->isEqualTo('users');
        $this->integer($event->getId())->isEqualTo(123);
    }

    public function testIndex()
    {
        $event = new Search\Event\DeleteEvent('users', 123);

        $event->setIndex('articles');

        $this->string($event->getIndex())->isEqualTo('articles');
    }

    public function testId()
    {
        $event = new Search\Event\DeleteEvent('users', 123);

        $event->setId(456);

        $this->integer($event->getId())->isEqualTo(456);
    }
}
