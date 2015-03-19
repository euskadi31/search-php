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
namespace Search\Tests\Units\Engine\SphinxQL;

use Search;

class Response extends Search\Test\Unit
{
    public function testConstructor()
    {
        $response = new Search\Engine\SphinxQL\Response(array(
            array(
                "id"        => 1,
                "agency_id" => 1,
                "weight"    => 2302
            ),
            array(
                "id"        => 5,
                "agency_id" => 1,
                "weight"    => 2302
            )
        ));

        $this->object($response)
            ->isInstanceOf('\Search\ResponseInterface');

        $this->integer($response->count())
            ->isEqualTo(2);

        $this->array($response->keys())
            ->isEqualTo(array(1, 5));

        $this->array($response->toArray())
            ->isEqualTo(array(
                1 => array(
                    "id"        => 1,
                    "agency_id" => 1,
                    "weight"    => 2302
                ),
                5 => array(
                    "id"        => 5,
                    "agency_id" => 1,
                    "weight"    => 2302
                )
            ));


        $this->boolean(isset($response[1]))->isTrue();

        $this->array($response[1])
            ->isEqualTo(array(
                    "id"        => 1,
                    "agency_id" => 1,
                    "weight"    => 2302
                ));

        $this->array(array(1, 5))
            ->isEqualTo($response->keys());

        $this->array($response->current())
            ->isEqualTo(array(
                    "id"        => 1,
                    "agency_id" => 1,
                    "weight"    => 2302
                ));

        $this->integer($response->key())
            ->isEqualTo(1);

        $this->boolean($response->valid())
            ->isTrue();

        $this->array($response->next())
            ->isEqualTo(array(
                    "id"        => 5,
                    "agency_id" => 1,
                    "weight"    => 2302
                ));

        $this->array($response->rewind())
            ->isEqualTo(array(
                    "id"        => 1,
                    "agency_id" => 1,
                    "weight"    => 2302
                ));

        $response[10] = array(
            "id"        => 10,
            "agency_id" => 1,
            "weight"    => 3454
        );

        $response[] = array(
            "id"        => 11,
            "agency_id" => 1,
            "weight"    => 3454
        );

        unset($response[10]);
    }
}
