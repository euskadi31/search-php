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
namespace Search\Tests\Units;

use Search;

class Term extends Search\Test\Unit
{
    public function testNormalize()
    {
        $data = array(
            'hello.world'       => 'hello world',
            'hello+world'       => 'hello world',
            'hello-world'       => 'hello world',
            'hello*world'       => 'hello world',
            'hello~world'       => 'hello world',
            'hello$world'       => 'hello world',
            'hello:world'       => 'hello world',
            'hello;world'       => 'hello world',
            'hello   world'     => 'hello world',
            '   hello world  '  => 'hello world',
            'HELLO WORLD'       => 'hello world'
        );

        foreach ($data as $input => $expected) {
            $this->string(Search\Term::normalize($input))->isEqualTo($expected);
        }
    }
}
