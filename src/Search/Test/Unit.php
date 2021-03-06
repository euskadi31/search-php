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
namespace Search\Test;

use \mageekguy\atoum;
use \mageekguy\atoum\factory;

abstract class Unit extends atoum\test
{
    public function __construct(factory $factory = null)
    {
        $this->setTestNamespace('Tests\\Units');
        parent::__construct($factory);
    }
}
