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
namespace Search\Tests\Units\Engine;

use Search;

class SphinxQL extends Search\Test\Unit
{
    public function testConstructor()
    {
        $search = new Search\Engine\SphinxQL();

        $this->object($search)->isInstanceOf('\Search\Engine\SphinxQL');
    }

    public function testCache()
    {
        $search = new Search\Engine\SphinxQL();

        $this->object($search->getCache())
            ->isInstanceOf('\Doctrine\Common\Cache\ArrayCache');

        $cache = new \mock\Doctrine\Common\Cache\ApcCache();

        $search->setCache($cache);

        $this->object($search->getCache())
            ->isInstanceOf('\Doctrine\Common\Cache\ApcCache');

        $this->object($search->getCache())
            ->isEqualTo($cache);

        $this->integer($search->getCacheLife())
            ->isEqualTo(0);

        $search->setCacheLife(10);

        $this->integer($search->getCacheLife())
            ->isEqualTo(10);

        $this->string($search->getCacheKey(
            'SELECT * FROM test WHERE MATCH(:term) OPTION ranker = proximity, user_weight = (title=100, content=20)',
            [
                ':term' => 'music'
            ]
        ))->isEqualTo('a6de6a1617a0cbf3291363e59ed23a1b0ebb1be2');
    }

    public function testEventDispatcher()
    {
        $search = new Search\Engine\SphinxQL();

        $this->object($search->getEventDispatcher())
            ->isInstanceOf('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface();

        $search->setEventDispatcher($dispatcher);

        $this->object($search->getEventDispatcher())
            ->isInstanceOf('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $this->object($search->getEventDispatcher())
            ->isEqualTo($dispatcher);
    }

    public function testGetPdo()
    {
        $search = new Search\Engine\SphinxQL();

        try {
            $this->object($search->getPdo())->isInstanceOf('\PDO');
        } catch (\PDOException $ex) {
        }

        $that = $this;

        $this->mockGenerator->orphanize('__construct');

        $pdo = new \mock\PDO();

        $this->calling($pdo)->setAttribute = function($key, $val) use ($that) {
            $that->integer($key)->isEqualTo(\PDO::ATTR_DEFAULT_FETCH_MODE);
            $that->integer($val)->isEqualTo(\PDO::FETCH_ASSOC);
        };

        $search = new Search\Engine\SphinxQL();
        $search->setPdo($pdo);

        $this->object($search->getPdo())->isEqualTo($pdo);
    }

    public function testDistanceField()
    {
        $search = new Search\Engine\SphinxQL();

        $this->string($search->getDistanceField())->isEqualTo('_distance');

        $search->setDistanceField('foo');
        $this->string($search->getDistanceField())->isEqualTo('foo');
    }

    public function testInsert()
    {
        $that = $this;

        $this->mockGenerator->orphanize('__construct');

        $pdo = new \mock\PDO();

        $pdos = new \mock\PDOStatement();

        $this->calling($pdos)->execute = function($params) {

            return true;
        };

        $this->calling($pdo)->prepare = function($sql) use ($that, $pdos) {

            $that->string("INSERT INTO test (id, name) VALUES (:id, :name)")->isEqualTo($sql);

            return $pdos;
        };

        $this->calling($pdo)->setAttribute = function($key, $val) use ($that) {
            $that->integer($key)->isEqualTo(\PDO::ATTR_DEFAULT_FETCH_MODE);
            $that->integer($val)->isEqualTo(\PDO::FETCH_ASSOC);
        };

        $search = new Search\Engine\SphinxQL();
        $search->setPdo($pdo);

        $dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface();

        $that = $this;

        $this->calling($dispatcher)->dispatch = function($eventName, \Symfony\Component\EventDispatcher\Event $event) use ($that) {
            $that->object($event)->isInstanceOf('\Search\Event\InsertEvent');
            $that->string($eventName)->isEqualTo('search.insert');
            $that->string($event->getIndex())->isEqualTo('test');
        };

        $search->setEventDispatcher($dispatcher);

        $this->boolean($search->insert('test', array(
            "id"    => 1,
            "name"  => "Axel"
        )))->isTrue();
    }

    public function testSearch()
    {
        $that = $this;

        $this->mockGenerator->orphanize('__construct');

        $pdo = new \mock\PDO();

        $pdos = new \mock\PDOStatement();

        $this->calling($pdos)->execute = function($params) {

            return true;
        };

        $this->calling($pdos)->fetchAll = function() {
            return array(
                array(
                    "id"    => 1,
                    "name"  => 'Music'
                )
            );
        };

        $this->calling($pdo)->prepare = function($sql) use ($that, $pdos) {

            $that->string("SELECT * FROM test WHERE MATCH(:term)")->isEqualTo($sql);

            return $pdos;
        };

        $this->calling($pdo)->setAttribute = function($key, $val) use ($that) {
            $that->integer($key)->isEqualTo(\PDO::ATTR_DEFAULT_FETCH_MODE);
            $that->integer($val)->isEqualTo(\PDO::FETCH_ASSOC);
        };

        $search = new Search\Engine\SphinxQL();
        $search->setPdo($pdo);

        $dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface();

        $this->calling($dispatcher)->dispatch = function($eventName, \Symfony\Component\EventDispatcher\Event $event) use ($that) {
            $that->object($event)->isInstanceOf('\Search\Event\ResponseEvent');
            $that->string($eventName)->isEqualTo('search.response');
            $that->string($event->getTerm())->isEqualTo('music');
        };

        $search->setEventDispatcher($dispatcher);

        $response = $search->search('music', 'test');

        $this->object($response)
            ->isInstanceOf('\Search\Engine\SphinxQL\Response');

        $this->array($response->keys())->isEqualTo(array(
            1
        ));

        $this->integer($response->count())->isEqualTo(1);
    }

    public function testSearchWithCache()
    {
        $that = $this;

        $this->mockGenerator->orphanize('__construct');

        $pdo = new \mock\PDO();

        $pdos = new \mock\PDOStatement();

        $this->calling($pdos)->execute = function($params) {

            return true;
        };

        $this->calling($pdos)->fetchAll = function() {
            return array(
                array(
                    "id"    => 1,
                    "name"  => 'Music'
                )
            );
        };

        $called = 0;

        $this->calling($pdo)->prepare = function($sql) use ($that, $pdos, &$called) {

            $that->string("SELECT * FROM test WHERE MATCH(:term)")->isEqualTo($sql);

            $called++;

            $that->integer($called)->isEqualTo(1);

            return $pdos;
        };

        $this->calling($pdo)->setAttribute = function($key, $val) use ($that) {
            $that->integer($key)->isEqualTo(\PDO::ATTR_DEFAULT_FETCH_MODE);
            $that->integer($val)->isEqualTo(\PDO::FETCH_ASSOC);
        };

        $search = new Search\Engine\SphinxQL();
        $search->setPdo($pdo);

        $dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface();

        $this->calling($dispatcher)->dispatch = function($eventName, \Symfony\Component\EventDispatcher\Event $event) use ($that) {
            $that->object($event)->isInstanceOf('\Search\Event\ResponseEvent');
            $that->string($eventName)->isEqualTo('search.response');
            $that->string($event->getTerm())->isEqualTo('music');
        };

        $search->setEventDispatcher($dispatcher);

        $response = $search->search('music', 'test');

        $response = $search->search('music', 'test');

        $this->object($response)
            ->isInstanceOf('\Search\Engine\SphinxQL\Response');

        $this->array($response->keys())->isEqualTo(array(
            1
        ));

        $this->integer($response->count())->isEqualTo(1);
    }

    public function testSearchWithFilter()
    {
        $that = $this;

        $this->mockGenerator->orphanize('__construct');

        $pdo = new \mock\PDO();

        $pdos = new \mock\PDOStatement();

        $this->calling($pdos)->execute = function($params) {

            return true;
        };

        $this->calling($pdos)->fetchAll = function() {
            return array(
                array(
                    "id"    => 1,
                    "name"  => 'Music'
                )
            );
        };

        $this->calling($pdo)->prepare = function($sql) use ($that, $pdos) {

            $that->string("SELECT * FROM test WHERE MATCH(:term) AND category_id = 123")->isEqualTo($sql);

            return $pdos;
        };

        $this->calling($pdo)->setAttribute = function($key, $val) use ($that) {
            $that->integer($key)->isEqualTo(\PDO::ATTR_DEFAULT_FETCH_MODE);
            $that->integer($val)->isEqualTo(\PDO::FETCH_ASSOC);
        };

        $search = new Search\Engine\SphinxQL();
        $search->setPdo($pdo);
        $search->setFilter('category_id', 123);

        $dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface();

        $search->setEventDispatcher($dispatcher);

        $response = $search->search('music', 'test');

        $this->object($response)
            ->isInstanceOf('\Search\Engine\SphinxQL\Response');

        $this->array($response->keys())->isEqualTo(array(
            1
        ));

        $this->integer($response->count())->isEqualTo(1);
    }

    public function testSearchWithMultiValueFilter()
    {
        $that = $this;

        $this->mockGenerator->orphanize('__construct');

        $pdo = new \mock\PDO();

        $pdos = new \mock\PDOStatement();

        $this->calling($pdos)->execute = function($params) {

            return true;
        };

        $this->calling($pdos)->fetchAll = function() {
            return array(
                array(
                    "id"    => 1,
                    "name"  => 'Music'
                )
            );
        };

        $this->calling($pdo)->prepare = function($sql) use ($that, $pdos) {

            $that->string("SELECT * FROM test WHERE MATCH(:term) AND category_id IN(123, 456)")->isEqualTo($sql);

            return $pdos;
        };

        $this->calling($pdo)->setAttribute = function($key, $val) use ($that) {
            $that->integer($key)->isEqualTo(\PDO::ATTR_DEFAULT_FETCH_MODE);
            $that->integer($val)->isEqualTo(\PDO::FETCH_ASSOC);
        };

        $search = new Search\Engine\SphinxQL();
        $search->setPdo($pdo);
        $search->setFilter('category_id', array(123, 456));

        $dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface();

        $search->setEventDispatcher($dispatcher);

        $response = $search->search('music', 'test');

        $this->object($response)
            ->isInstanceOf('\Search\Engine\SphinxQL\Response');

        $this->array($response->keys())->isEqualTo(array(
            1
        ));

        $this->integer($response->count())->isEqualTo(1);
    }

    public function testSearchWithFilters()
    {
        $that = $this;

        $this->mockGenerator->orphanize('__construct');

        $pdo = new \mock\PDO();

        $pdos = new \mock\PDOStatement();

        $this->calling($pdos)->execute = function($params) {

            return true;
        };

        $this->calling($pdos)->fetchAll = function() {
            return array(
                array(
                    "id"    => 1,
                    "name"  => 'Music'
                )
            );
        };

        $this->calling($pdo)->prepare = function($sql) use ($that, $pdos) {

            $that->string("SELECT * FROM test WHERE MATCH(:term) AND category_id = 123 AND agency_id = 12")->isEqualTo($sql);

            return $pdos;
        };

        $this->calling($pdo)->setAttribute = function($key, $val) use ($that) {
            $that->integer($key)->isEqualTo(\PDO::ATTR_DEFAULT_FETCH_MODE);
            $that->integer($val)->isEqualTo(\PDO::FETCH_ASSOC);
        };

        $search = new Search\Engine\SphinxQL();
        $search->setPdo($pdo);
        $search->setFilter('category_id', 123);
        $search->setFilter('agency_id', 12);

        $dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface();

        $search->setEventDispatcher($dispatcher);

        $response = $search->search('music', 'test');

        $this->object($response)
            ->isInstanceOf('\Search\Engine\SphinxQL\Response');

        $this->array($response->keys())->isEqualTo(array(
            1
        ));

        $this->integer($response->count())->isEqualTo(1);
    }

    public function testSearchWithOrderBy()
    {
        $that = $this;

        $this->mockGenerator->orphanize('__construct');

        $pdo = new \mock\PDO();

        $pdos = new \mock\PDOStatement();

        $this->calling($pdos)->execute = function($params) {

            return true;
        };

        $this->calling($pdos)->fetchAll = function() {
            return array(
                array(
                    "id"    => 1,
                    "name"  => 'Music'
                )
            );
        };

        $this->calling($pdo)->prepare = function($sql) use ($that, $pdos) {

            $that->string("SELECT * FROM test WHERE MATCH(:term) ORDER BY id DESC")->isEqualTo($sql);

            return $pdos;
        };

        $this->calling($pdo)->setAttribute = function($key, $val) use ($that) {
            $that->integer($key)->isEqualTo(\PDO::ATTR_DEFAULT_FETCH_MODE);
            $that->integer($val)->isEqualTo(\PDO::FETCH_ASSOC);
        };

        $search = new Search\Engine\SphinxQL();
        $search->setPdo($pdo);
        $search->setOrderBy('id DESC');

        $dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface();

        $search->setEventDispatcher($dispatcher);

        $response = $search->search('music', 'test');

        $this->object($response)
            ->isInstanceOf('\Search\Engine\SphinxQL\Response');

        $this->array($response->keys())->isEqualTo(array(
            1
        ));

        $this->integer($response->count())->isEqualTo(1);
    }

    public function testSearchWithOption()
    {
        $that = $this;

        $this->mockGenerator->orphanize('__construct');

        $pdo = new \mock\PDO();

        $pdos = new \mock\PDOStatement();

        $this->calling($pdos)->execute = function($params) {

            return true;
        };

        $this->calling($pdos)->fetchAll = function() {
            return array(
                array(
                    "id"    => 1,
                    "name"  => 'Music'
                )
            );
        };

        $this->calling($pdo)->prepare = function($sql) use ($that, $pdos) {

            $that->string("SELECT * FROM test WHERE MATCH(:term) OPTION ranker = proximity")->isEqualTo($sql);

            return $pdos;
        };

        $this->calling($pdo)->setAttribute = function($key, $val) use ($that) {
            $that->integer($key)->isEqualTo(\PDO::ATTR_DEFAULT_FETCH_MODE);
            $that->integer($val)->isEqualTo(\PDO::FETCH_ASSOC);
        };

        $search = new Search\Engine\SphinxQL();
        $search->setPdo($pdo);
        $search->addOption('ranker', 'proximity');

        $dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface();

        $search->setEventDispatcher($dispatcher);

        $response = $search->search('music', 'test');

        $this->object($response)
            ->isInstanceOf('\Search\Engine\SphinxQL\Response');

        $this->array($response->keys())->isEqualTo(array(
            1
        ));

        $this->integer($response->count())->isEqualTo(1);
    }

    public function testSearchWithOptions()
    {
        $that = $this;

        $this->mockGenerator->orphanize('__construct');

        $pdo = new \mock\PDO();

        $pdos = new \mock\PDOStatement();

        $this->calling($pdos)->execute = function($params) {

            return true;
        };

        $this->calling($pdos)->fetchAll = function() {
            return array(
                array(
                    "id"    => 1,
                    "name"  => 'Music'
                )
            );
        };

        $this->calling($pdo)->prepare = function($sql) use ($that, $pdos) {

            $that->string("SELECT * FROM test WHERE MATCH(:term) OPTION ranker = proximity, user_weight = (title=100, content=20)")->isEqualTo($sql);

            return $pdos;
        };

        $this->calling($pdo)->setAttribute = function($key, $val) use ($that) {
            $that->integer($key)->isEqualTo(\PDO::ATTR_DEFAULT_FETCH_MODE);
            $that->integer($val)->isEqualTo(\PDO::FETCH_ASSOC);
        };

        $search = new Search\Engine\SphinxQL();
        $search->setPdo($pdo);
        $search->addOption('ranker', 'proximity');
        $search->addOption('user_weight', '(title=100, content=20)');

        $dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface();

        $search->setEventDispatcher($dispatcher);

        $response = $search->search('music', 'test');

        $this->object($response)
            ->isInstanceOf('\Search\Engine\SphinxQL\Response');

        $this->array($response->keys())->isEqualTo(array(
            1
        ));

        $this->integer($response->count())->isEqualTo(1);
    }


    public function testSearchWithFilterRange()
    {
        $that = $this;

        $this->mockGenerator->orphanize('__construct');

        $pdo = new \mock\PDO();

        $pdos = new \mock\PDOStatement();

        $this->calling($pdos)->execute = function($params) {

            return true;
        };

        $this->calling($pdos)->fetchAll = function() {
            return array(
                array(
                    "id"    => 1,
                    "name"  => 'Music'
                )
            );
        };

        $this->calling($pdo)->prepare = function($sql) use ($that, $pdos) {

            $that->string("SELECT * FROM test WHERE MATCH(:term) AND (price BETWEEN 30 AND 70)")->isEqualTo($sql);

            return $pdos;
        };

        $this->calling($pdo)->setAttribute = function($key, $val) use ($that) {
            $that->integer($key)->isEqualTo(\PDO::ATTR_DEFAULT_FETCH_MODE);
            $that->integer($val)->isEqualTo(\PDO::FETCH_ASSOC);
        };

        $search = new Search\Engine\SphinxQL();
        $search->setPdo($pdo);
        $search->setFilterRange('price', 30, 70);

        $dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface();

        $search->setEventDispatcher($dispatcher);

        $response = $search->search('music', 'test');

        $this->object($response)
            ->isInstanceOf('\Search\Engine\SphinxQL\Response');

        $this->array($response->keys())->isEqualTo(array(
            1
        ));

        $this->integer($response->count())->isEqualTo(1);
    }

    public function testSearchWithGeoFilter()
    {
        $that = $this;

        $this->mockGenerator->orphanize('__construct');

        $pdo = new \mock\PDO();

        $pdos = new \mock\PDOStatement();

        $this->calling($pdos)->execute = function($params) {

            return true;
        };

        $this->calling($pdos)->fetchAll = function() {
            return array(
                array(
                    "id"    => 1,
                    "name"  => 'Music'
                )
            );
        };

        $this->calling($pdo)->prepare = function($sql) use ($that, $pdos) {

            $that->string("SELECT *, GEODIST(48.824827, 2.369667, lat, long) AS _distance FROM test WHERE MATCH(:term) AND _distance < 10000 ORDER BY _distance ASC")->isEqualTo($sql);

            return $pdos;
        };

        $this->calling($pdo)->setAttribute = function($key, $val) use ($that) {
            $that->integer($key)->isEqualTo(\PDO::ATTR_DEFAULT_FETCH_MODE);
            $that->integer($val)->isEqualTo(\PDO::FETCH_ASSOC);
        };

        $search = new Search\Engine\SphinxQL();
        $search->setPdo($pdo);
        $search->setGeoFilter('lat', 'long', 48.82482710, 2.36966660, 10000);

        $dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface();

        $search->setEventDispatcher($dispatcher);

        $response = $search->search('music', 'test');

        $this->object($response)
            ->isInstanceOf('\Search\Engine\SphinxQL\Response');

        $this->array($response->keys())->isEqualTo(array(
            1
        ));

        $this->integer($response->count())->isEqualTo(1);
    }


    public function testSearchWithLimit()
    {
        $that = $this;

        $this->mockGenerator->orphanize('__construct');

        $pdo = new \mock\PDO();

        $pdos = new \mock\PDOStatement();

        $this->calling($pdos)->execute = function($params) {

            return true;
        };

        $this->calling($pdos)->fetchAll = function() {
            return array(
                array(
                    "id"    => 1,
                    "name"  => 'Music'
                )
            );
        };

        $this->calling($pdo)->prepare = function($sql) use ($that, $pdos) {

            $that->string("SELECT * FROM test WHERE MATCH(:term) LIMIT 1000")->isEqualTo($sql);

            return $pdos;
        };

        $this->calling($pdo)->setAttribute = function($key, $val) use ($that) {
            $that->integer($key)->isEqualTo(\PDO::ATTR_DEFAULT_FETCH_MODE);
            $that->integer($val)->isEqualTo(\PDO::FETCH_ASSOC);
        };

        $search = new Search\Engine\SphinxQL();
        $search->setPdo($pdo);
        $search->setLimit(1000);

        $dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface();

        $search->setEventDispatcher($dispatcher);

        $this->integer($search->getLimit())->isEqualTo(1000);

        $response = $search->search('music', 'test');

        $this->object($response)
            ->isInstanceOf('\Search\Engine\SphinxQL\Response');

        $this->array($response->keys())->isEqualTo(array(
            1
        ));

        $this->integer($response->count())->isEqualTo(1);
    }

}
