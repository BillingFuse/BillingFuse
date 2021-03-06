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

class Fuse_Update
{
    /**
     * @var \Fuse_Di
     */
    protected $di = null;

    /**
     * @param \Fuse_Di $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return \Fuse_Di
     */
    public function getDi()
    {
        return $this->di;
    }

    private $_url = 'http://api.billingfuse.com/compare-version.php';

    public function __construct()
    {
        if(defined('BF_VERSION_URL') && BF_VERSION_URL) {
            $this->_url = BF_VERSION_URL;
        }
    }

    /**
     * Returns latest information
     */
    private function _getLatestVersionInfo()
    {
        return $this->di['tools']->cache_function(array($this, 'getJson'), array(), 86400);
    }

    /**
     * Return latest version number
     * @return string
     */
    public function getLatestVersion()
    {
        $response = $this->_getLatestVersionInfo();
        if(!is_object($response)) {
            return Fuse_Version::VERSION;
        }
        return $response->version;
    }

    /**
     * Latest version link
     * @return string
     */
    public function getLatestVersionDownloadLink()
    {
        $response = $this->_getLatestVersionInfo();
        if(isset($response->update)) {
            return $response->update;
        }
        return $response->link;
    }

    /**
     * Check if we need to update current BillingFuse version
     * @return bool
     */
    public function getCanUpdate()
    {
        $version = $this->getLatestVersion();
        $result = Fuse_Version::compareVersion($version);
        return ($result > 0);
    }

    /**
     * Check if given file is same as original
     * @param string $file - filepath
     * @return bool
     */
    private function isHashValid($file)
    {
        if(!file_exists($file)) {
            return false;
        }
        
        $response = $this->_getLatestVersionInfo();
        $hash = md5($response->version.filesize($file));
        return ($hash == $response->hash);
    }

    public function getJson()
    {
        $url = $this->_url . '?current=' . Fuse_Version::VERSION;
        $curl = new Fuse_Curl($url);
        $curl->request();
        $response = $curl->getBody();
        return json_decode($response);
    }

    /**
     * Perform update
     *
     * @throws Exception
     */
    public function performUpdate()
    {
        if(!$this->getCanUpdate()) {
            throw new LogicException('You have latest version of BillingFuse. You do not need to update.');
        }

        error_log('Started BillingFuse auto-update script');
        $latest_version = $this->getLatestVersion();
        $latest_version_archive = BF_PATH_CACHE.DIRECTORY_SEPARATOR.$latest_version.'.zip';

        // download latest archive from link
        $content = $this->di['tools']->file_get_contents($this->getLatestVersionDownloadLink());
        $f = fopen($latest_version_archive,'wb');
        fwrite($f,$content,strlen($content));
        fclose($f);

        //@todo validate downloaded file hash

        // Extract latest archive on top of current version
        $ff = new Fuse_Zip($latest_version_archive);
        $ff->decompress(BF_PATH_ROOT);

        if(file_exists(BF_PATH_ROOT.'/update.php')) {
            error_log('Calling update.php script from auto-updater');
            $this->di['tools']->file_get_contents(BF_URL.'update.php');
        }
        
        // clean up things
        $this->di['tools']->emptyFolder(BF_PATH_CACHE);
        $this->di['tools']->emptyFolder(BF_PATH_ROOT.'/install');
        rmdir(BF_PATH_ROOT.'/install');
        return true;
    }
}