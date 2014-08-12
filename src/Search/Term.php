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
namespace Search;

class Term
{
    /**
     *
     * @param String $name
     * @return String
     */
    public static function normalize($name)
    {
        $name = html_entity_decode($name);
        $name = str_replace('-', ' ', $name);
        $name = str_replace('*', ' ', $name);
        $name = str_replace('.', ' ', $name);
        // $sString = str_replace('/', ' ', $sString);
        $name = str_replace('+', ' ', $name);
        $name = str_replace('~', ' ', $name);
        $name = str_replace('$', ' ', $name);
        $name = str_replace(':', ' ', $name);
        $name = str_replace(';', ' ', $name);
        $name = trim($name);
        $name = preg_replace('/\s{2,}/', ' ', $name);

        return mb_strtolower($name);
    }
}

