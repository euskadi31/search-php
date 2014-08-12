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
namespace Search\Tests\Units\Engine\Sphinx;

use Search;

class Response extends Search\Test\Unit
{
    public function testConstructor()
    {
        $response = new Search\Engine\Sphinx\Response(array(
            "status" => 0,
            "error" => "",
            "warning" => "",
            "total" => 0,
            "matches" => array()
        ));

        $this->object($response)
            ->isInstanceOf('\Search\ResponseInterface');

        $this->integer($response->count())
            ->isEqualTo(0);
    }

    public function testResponseOk()
    {
        $response = new Search\Engine\Sphinx\Response(array(
            "status" => 0,
            "error" => "",
            "warning" => "",
            "total" => 1,
            "matches" => array(
                1 => array()
            )
        ));

        $this->boolean($response->isError())->isFalse();
        $this->boolean($response->isWarning())->isFalse();
        $this->boolean($response->isSuccessful())->isTrue();
    }

    public function testResponseError()
    {
        $response = new Search\Engine\Sphinx\Response(array(
            "status" => 1,
            "error" => "Connetion lost",
            "warning" => "",
            "total" => 0,
            "matches" => array()
        ));

        $this->boolean($response->isError())->isTrue();
        $this->boolean($response->isWarning())->isFalse();
        $this->boolean($response->isSuccessful())->isFalse();

        $this->string($response->getError())->isEqualTo("Connetion lost");

        $this->array($response->getMessages())->isEqualTo(array(
            "Connetion lost"
        ));
    }

    public function testResponseWarning()
    {
        $response = new Search\Engine\Sphinx\Response(array(
            "status" => 3,
            "error" => "",
            "warning" => "foo",
            "total" => 0,
            "matches" => array()
        ));

        $this->boolean($response->isError())->isFalse();
        $this->boolean($response->isWarning())->isTrue();
        $this->boolean($response->isSuccessful())->isTrue();

        $this->string($response->getWarning())->isEqualTo("foo");

        $this->array($response->getMessages())->isEqualTo(array(
            "foo"
        ));
    }
}
