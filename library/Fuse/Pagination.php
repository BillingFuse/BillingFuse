<?php
/*
*
* BillingFuse
*
* @copyright 2016 BillingFuse International Limited.
*
* @license Apache V2.0
*
* THIS SOURCE CODE FORM IS SUBJECT TO THE TERMS OF THE PUBLIC
* APACHE LICENSE V2.0. A COMPLETE COPY OF THE LICENSE TEXT IS
* INCLUDED IN THE LICENSE FILE. 
*
*/

use Fuse\InjectionAwareInterface;

class Fuse_Pagination implements InjectionAwareInterface
{
    protected $di = null;
    protected $per_page = 100;

    public function setDi($di)
    {
        $this->di = $di;
    }

    public function getDi()
    {
        return $this->di;
    }

    /**
     * @return int
     */
    public function getPer_page()
    {
        return $this->per_page;
    }

    public function getSimpleResultSet($q, $values, $per_page = 100, $page = null)
    {
        if (is_null($page)){
            $page = $this->di['request']->getQuery('page', "int", 1);
        }
        $per_page = $this->di['request']->getQuery('per_page', "int", $per_page);

        $offset = ($page - 1) * $per_page;

        $sql = $q;
        $sql .= sprintf(' LIMIT %s,%s', $offset, $per_page);
        $result = $this->di['db']->getAll($sql, $values);

        $exploded = explode('FROM', $q);
        $sql = 'SELECT count(1) FROM ' . $exploded[1];
        $total = $this->di['db']->getCell($sql , $values);

        $pages = ($per_page > 1) ? (int)ceil($total / $per_page) : 1;
        return array(
            "pages"             => $pages,
            "page"              => $page,
            "per_page"          => $per_page,
            "total"             => $total,
            "list"              => $result,
        );
    }

    public function getAdvancedResultSet($q, $values, $per_page = 100)
    {
        $page = $this->di['request']->getQuery('page', "int", 1);
        $per_page = $this->di['request']->getQuery('per_page', "int", $per_page);

        $offset = ($page - 1) * $per_page;
        $q = str_replace('SELECT ', 'SELECT SQL_CALC_FOUND_ROWS ', $q);
        $q .= sprintf(' LIMIT %s,%s', $offset, $per_page);
        $result = $this->di['db']->getAll($q, $values);
        $total = $this->di['db']->getCell('SELECT FOUND_ROWS();');

        $pages = ($per_page > 1) ? (int)ceil($total / $per_page) : 1;
        return array(
            "pages"             => $pages,
            "page"              => $page,
            "per_page"          => $per_page,
            "total"             => $total,
            "list"              => $result,
        );
    }
}
