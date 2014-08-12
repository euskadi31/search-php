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
        $response = new Search\Engine\SphinxQL\Response([
            [
                "id"        => 1,
                "agency_id" => 1,
                "weight"    => 2302
            ],
            [
                "id"        => 5,
                "agency_id" => 1,
                "weight"    => 2302
            ]
        ]);

        $this->object($response)
            ->isInstanceOf('\Search\ResponseInterface');

        $this->integer($response->count())
            ->isEqualTo(2);

        $this->array($response->keys())
            ->isEqualTo([1, 5]);

        $this->array($response->toArray())
            ->isEqualTo([
                1 => [
                    "id"        => 1,
                    "agency_id" => 1,
                    "weight"    => 2302
                ],
                5 => [
                    "id"        => 5,
                    "agency_id" => 1,
                    "weight"    => 2302
                ]
            ]);
    }
}
