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

use RuntimeException;
use PDO;
use Search\Engine\SphinxQL\Response;

class SphinxQL implements SearchInterface, IndexerInterface
{
    /**
     *
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var integer
     */
    protected $port;

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var string
     */
    protected $order_by;

    /**
     *
     * @param string  $host
     * @param integer $port
     */
    public function __construct($host = "127.0.0.1", $port = 9306)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * Set PDO object
     *
     * @param PDO $pdo
     */
    public function setPdo(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );

        return $this;
    }

    /**
     * Get PDO object
     *
     * @return PDO
     */
    public function getPdo()
    {
        if (empty($this->pdo)) {
            $this->pdo = new PDO(sprintf("mysql:host=%s;port=%d", $this->host, $this->port));
            $this->pdo->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );
        }

        return $this->pdo;
    }

    /**
     * Set filter
     *
     * @param string $key
     * @param mixed $value
     */
    public function setFilter($key, $value)
    {
        $this->filters[$key] = (array)$value;

        return $this;
    }

    /**
     * Add option
     *
     * @param string $key
     * @param string $value
     */
    public function addOption($key, $value)
    {
        $this->options[$key] = $value;

        return $this;
    }

    /**
     * Set order by
     *
     * @param string $order_by
     */
    public function setOrderBy($order_by)
    {
        $this->order_by = $order_by;

        return $this;
    }

    /**
     *
     * @param  string $type INSERT or REPLACE
     * @param  string $name index name
     * @param  array  $data
     * @return boolean
     */
    protected function _into($type, $name, array $data)
    {
        $sql = sprintf(
            '%s INTO %s (%s) VALUES (:%s)',
            strtoupper($type),
            $name,
            implode(', ', array_keys($data)),
            implode(', :', array_keys($data))
        );

        $parameters = array();

        foreach ($data as $key => $value) {
            $parameters[':' . trim($key, ':')] = $value;
        }

        $query = $this->getPdo()->prepare($sql);

        return $query->execute($parameters);
    }

    /**
     *
     * @param  string $name index name
     * @param  array  $data
     * @return boolean
     */
    public function insert($name, array $data)
    {
        return $this->_into('INSERT', $name, $data);
    }

    /**
     *
     * @param  string $name index name
     * @param  array  $data
     * @return int
     */
    public function update($name, array $data)
    {
        return $this->_into('REPLACE', $name, $data);
    }

    /**
     *
     * @param  string $name index name
     * @param  integer|array  $id
     * @return boolean
     */
    public function delete($name, $id)
    {
        $primary = 'id';

        if (is_array($id)) {
            $primary = key($id);
            $id = current($id);
        }

        $sql = sprintf('DELETE FROM %s WHERE %s = %d', $name, $primary, $id);

        $query = $this->getPdo()->prepare($sql);

        return $query->execute();
    }

    /**
     *
     * @param  string $sql  the SphinxQL string
     * @param  array  $data
     * @return PDOStatement
     */
    public function fetch($sql, array $data)
    {
        $parameters = array();

        foreach ($data as $key => $value) {
            $parameters[':' . trim($key, ':')] = $value;
        }

        $query = $this->getPdo()->prepare($sql);
        $query->execute($parameters);

        return $query->fetchAll();
    }

    /**
     *
     * @param  string $term
     * @param  string $index
     * @param  array  $fields
     * @return \Search\Engine\SphinxQL\Response
     */
    public function search($term, $index, array $fields = array('*'))
    {
        $sql = sprintf(
            'SELECT %s FROM %s WHERE MATCH(:term)',
            implode(', ', $fields),
            implode(', ', (array)$index)
        );

        if (!empty($this->filters)) {
            $parts = array();

            foreach ($this->filters as $key => $value) {
                if (count($value) > 1) {
                    $parts[] = sprintf('%s IN(%s)', $key, implode(', ', $value));
                } else {
                    $parts[] = sprintf('%s = %d', $key, (int)$value[0]);
                }
            }

            $sql .= ' AND ' . implode(' AND ', $parts);
        }

        if (!empty($this->order_by)) {
            $sql .= ' ORDER BY ' . $this->order_by;
        }

        if (!empty($this->options)) {
            $parts = array();

            foreach ($this->options as $key => $value) {
                $parts[] = sprintf("%s = %s", $key, $value);
            }

            $sql .= ' OPTION ' . implode(', ', $parts);
        }

        return new Response($this->fetch($sql, array(
            'term' => $term
        )));
    }
}
