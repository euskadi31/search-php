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

class InsertEvent extends Search\Test\Unit
{
    public function testConstructor()
    {
        $event = new Search\Event\InsertEvent('users', array(
            'id' => 123,
            'name' => 'Kiefer Sutherland'
        ));

        $this->object($event)->isInstanceOf('\Symfony\Component\EventDispatcher\Event');

        $this->string($event->getIndex())->isEqualTo('users');
        $this->array($event->getData())->isEqualTo(array(
            'id' => 123,
            'name' => 'Kiefer Sutherland'
        ));
    }

    public function testIndex()
    {
        $event = new Search\Event\InsertEvent('users', array(
            'id' => 123,
            'name' => 'Kiefer Sutherland'
        ));

        $event->setIndex('articles');

        $this->string($event->getIndex())->isEqualTo('articles');
    }

    public function testData()
    {
        $event = new Search\Event\InsertEvent('users', array(
            'id' => 123,
            'name' => 'Kiefer Sutherland'
        ));

        $event->setData(array(
            'id' => 456,
            'name' => 'Kiefer Sutherland'
        ));

        $this->array($event->getData())->isEqualTo(array(
            'id' => 456,
            'name' => 'Kiefer Sutherland'
        ));
    }
}
