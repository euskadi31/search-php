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
namespace Search\Engine;

use Search\Engine\Sphinx\Response;
use RuntimeException;
use SphinxClient;

class Sphinx extends SphinxClient implements SearchInterface
{
    /**
     *
     * @param  string $term
     * @param  string $index
     * @param  array  $fields
     * @return \Search\Engine\Sphinx\Response
     */
    public function search($term, $index, array $fields = array()) {
        return $this->query($term, $index);
    }

    public function query($query, $index = "*", $comment = null)
    {
        $results = parent::query($query, $index, $comment);

        if (empty($results)) {
            throw new RuntimeException(sprintf('Sphinx: %s', $this->getLastError()));
        }

        return new Response($results);
    }
}
