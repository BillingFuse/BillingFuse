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


namespace Fuse\Mod\Redirect;

class Service implements \Fuse\InjectionAwareInterface
{
    protected $di;

    /**
     * @param mixed $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return mixed
     */
    public function getDi()
    {
        return $this->di;
    }

    public function getRedirects()
    {
        $sql='
            SELECT id, meta_key as path, meta_value as target
            FROM extension_meta
            WHERE extension = "mod_redirect"
            ORDER BY id ASC
        ';
        return $this->di['db']->getAll($sql);
    }

    public function getRedirectByPath($path){
        $sql='
            SELECT meta_value
            FROM extension_meta
            WHERE extension = "mod_redirect"
            AND meta_key = :path
            LIMIT 1
        ';
        return $this->di['db']->getCell($sql, array('path'=>$path));
    }
}
