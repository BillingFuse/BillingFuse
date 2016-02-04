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

class Fuse_Url implements Fuse\InjectionAwareInterface
{
    protected $di;
    protected $baseUri;

    public function setDi($di)
    {
        $this->di = $di;
    }

    public function getDi()
    {
        return $this->di;
    }

    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * Generates a URL
     */
    public function get ($uri)
    {
        return $this->baseUri . $uri;
    }

    /**
     * @param string $uri
     */
    public function link($uri = null, $params = array())
    {
        $uri = trim($uri, '/');
        $link =$this->baseUri .'index.php?_url=/' . $uri;
        if(BF_SEF_URLS) {
            $link = $this->baseUri . $uri;
            if (!empty($params)){
                $link .= '?';
            }
        }

        if(!empty($params)) {
            $link  .= '&' . http_build_query($params);
        }
        return $link;
    }

    public function adminLink($uri, $params = array())
    {
        $uri = trim($uri, '/');
        $prefix = $this->di['config']['admin_area_prefix'];
        $uri = $prefix . '/' . $uri;
        return $this->link($uri, $params);
    }
}