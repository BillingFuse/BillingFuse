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

/**
 * All public methods in this class are exposed to public. Always think
 * what kind of information you are exposing. Emails, passwords and other 
 * information should NOT be returned by functions in this class
 * 
 * This module can be called from API or in template
 * 
 */

namespace Fuse\Mod\Example\Api;

class Guest extends \Api_Abstract
{
    /**
     * Get readme.md file contents
     */
    public function readme($data)
    {
        $readme = $this->di['tools']->file_get_contents(BF_PATH_MODS . '/Example/README.md');
        return $readme;
    }
    
    /**
     * Get Top Songs from iTunes Store
     * @param int $limit - define songs limit
     */
    public function top_songs($data)
    {
        $limit = $this->di['array_get']($data, 'limit', 10);
        $feed = "http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/ws/RSS/topsongs/limit=$limit/xml";
        $top_songs = $this->di['tools']->file_get_contents($feed);
        $xml = simplexml_load_string($top_songs);
        $array = array();
        $this->_convertXmlObjToArr($xml, $array);
        return $array;
    }
    
    private function _convertXmlObjToArr($obj, &$arr)
    { 
        $children = $obj->children(); 
        foreach ($children as $elementName => $node) 
        { 
            $nextIdx = count($arr); 
            $arr[$nextIdx] = array(); 
            $arr[$nextIdx]['_name'] = strtolower((string)$elementName);
            $arr[$nextIdx]['_attributes'] = array();
            $attributes = $node->attributes(); 
            foreach ($attributes as $attributeName => $attributeValue) 
            { 
                $attribName = strtolower(trim((string)$attributeName)); 
                $attribVal = trim((string)$attributeValue); 
                $arr[$nextIdx]['_attributes'][$attribName] = $attribVal;
            } 
            $text = (string)$node; 
            $text = trim($text); 
            if (strlen($text) > 0) 
            { 
                $arr[$nextIdx]['_text'] = $text;
            } 
            $arr[$nextIdx]['_children'] = array();
            $this->_convertXmlObjToArr($node, $arr[$nextIdx]['_children']);
        } 
        return; 
    }  
}