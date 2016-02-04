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

class Fuse_LogStream
{
    private $_stream = NULL;

    /**
     * @param string $streamOrUrl
     */
    public function __construct($streamOrUrl, $mode = null)
    {
        // Setting the default
        if (null === $mode) {
            $mode = 'a';
        }

        if (is_resource($streamOrUrl)) {
            if (get_resource_type($streamOrUrl) != 'stream') {
                throw new \Fuse_Exception('Resource is not a stream');
            }

            if ($mode != 'a') {
                throw new \Fuse_Exception('Mode cannot be changed on existing streams');
            }

            $this->_stream = $streamOrUrl;
        } else {
            if (is_array($streamOrUrl) && isset($streamOrUrl['stream'])) {
                $streamOrUrl = $streamOrUrl['stream'];
            }

            if(!file_exists($streamOrUrl)) {
                @touch($streamOrUrl);
            }

            if (! $this->_stream = @fopen($streamOrUrl, $mode, false)) {
                throw new \Fuse_Exception(":stream cannot be opened with mode :mode", array(':stream'=>$streamOrUrl, ':mode'=>$mode));
            }
        }
    }

    public function write($event)
    {
        $output = '%timestamp% %priorityName% (%priority%): %message%'.PHP_EOL;
        foreach ($event as $name => $value) {
            if ((is_object($value) && !method_exists($value,'__toString'))
                || is_array($value)
            ) {
                $value = gettype($value);
            }
            $output = str_replace("%$name%", $value, $output);
        }

        if (false === @fwrite($this->_stream, $output)) {
            throw new \Fuse_Exception("Unable to write to stream");
        }
    }
}
