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

class Fuse_RequestFile extends SplFileInfo
{
    protected $name;


    public function __construct(array $file)
    {
        $this->name = $file['name'];
        parent::__construct($file['tmp_name']);
    }


    public function getName()
    {
        return $this->name;
    }
}