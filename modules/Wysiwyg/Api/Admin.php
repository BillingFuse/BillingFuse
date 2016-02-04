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

namespace Fuse\Mod\Wysiwyg\Api;

class Admin extends \Api_Abstract
{
    public function editor()
    {
        $mod = $this->di['mod']('wysiwyg');
        $config = $mod->getConfig();
        return $this->di['array_get']($config, 'editor', 'markitup');
    }

    public function editors()
    {
        return array(
            'markitup'  =>  'markItUp',
            'ckeditor'  =>  'CKEditor',
        );
    }
}