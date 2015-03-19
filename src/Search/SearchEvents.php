<?php
/**
 * @package     Search
 * @author      Axel Etcheverry <axel@etcheverry.biz>
 * @copyright   Copyright (c) 2014-2015 Axel Etcheverry (https://twitter.com/euskadi31)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @namespace
 */
namespace Search;

final class SearchEvents
{
    const RESPONSE  = 'search.response';
    const REQUEST   = 'search.request';
    const INSERT    = 'search.insert';
    const UPDATE    = 'search.update';
    const DELETE    = 'search.delete';
}
