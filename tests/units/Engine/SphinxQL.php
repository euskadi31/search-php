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
        $search->setFilter('category_id', [123, 456]);

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

        $response = $search->search('music', 'test');

        $this->object($response)
            ->isInstanceOf('\Search\Engine\SphinxQL\Response');

        $this->array($response->keys())->isEqualTo(array(
            1
        ));

        $this->integer($response->count())->isEqualTo(1);
    }
}
