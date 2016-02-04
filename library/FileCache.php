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

class FileCache
{
    protected $_cacheDir = '';

    /**
     * Constructor
     */
    public function __construct($cacheDir = null)
    {
        if(is_null($cacheDir)) {
            $cacheDir = BF_PATH_CACHE;
        }

        if ($cacheDir) {
            if (!is_dir($cacheDir)) {
                throw new FileCacheException('The specified cache directory is invalid.');
            }
            $this->_cacheDir = $cacheDir;
        }
    }

    /**
     * Save data to the specified cache file
     */
    public function set($key, $data)
    {
        $cacheFile = $this->getCacheFile($key);
        if (!file_put_contents($cacheFile, json_encode($data))) {
            throw new FileCacheException('Error saving data with the key ' . $key . ' to the cache file.');
        }
        return $this;
    }

    /**
     * Get data from the specified cache file
     */
    public function get($key)
    {
        if ($this->exists($key)) {
            $cacheFile = $this->getCacheFile($key);
            if (!$data = json_decode(file_get_contents($cacheFile), true)) {
                throw new FileCacheException('Error reading data with the key ' . $key . ' from the cache file.');
            }
            return $data;
        }
        return null;
    }

    /**
     * Delete the specified cache file
     */
    public function delete($key)
    {
        if ($this->exists($key)) {
            $cacheFile = $this->getCacheFile($key);
            if (!unlink($cacheFile)) {
                throw new FileCacheException('Error deleting the file cache with key ' . $key);
            }
            return true;
        }
        return false;
    }

    /**
     * Check if the specified cache file exists
     */
    public function exists($key)
    {
        $cacheFile = $this->getCacheFile($key);
        return file_exists($cacheFile);
    }

    /**
     * Get the specified cache file
     */
    protected function getCacheFile($key)
    {
        $key = $this->getCacheKey($key);
        return $this->_cacheDir . DIRECTORY_SEPARATOR . $key . '.cache';
    }

    /**
     * Convert cache key to unreadable value
     */
    protected function getCacheKey($raw)
    {
        return strtolower(md5($raw . 'Iip6tPwfNi95gugl0e6'));
    }
}