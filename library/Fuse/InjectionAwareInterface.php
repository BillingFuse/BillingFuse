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

namespace Fuse;

interface InjectionAwareInterface
{
    /**
     * @param \Fuse_Di $di
     * @return void
     */
    public function setDi($di);

    /**
     * @return \Fuse_Di
     */
    public function getDi ();
}