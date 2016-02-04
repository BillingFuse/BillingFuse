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

class Fuse_License implements \Fuse\InjectionAwareInterface
{
    protected $di;

    public function setDi($di)
    {
        $this->di = $di;
    }

    public function getDi()
    {
        return $this->di;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        $license = $this->di['config']['license'];
        if(!$license || $license == '') {
            throw new \Fuse_Exception('BillingFuse license key must be defined in config.php file.', null, 315);
        }
        return $license;
    }

    public function check()
    {
        if(!$this->isValid()) {
            throw new \Fuse_Exception('License is not valid');
        }
    }

    public function isValid()
    {
        try {
            $this->getDetails();
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }

    public function isPro()
    {
        return ($this->getBFType() == \Fuse_Version::TYPE_PRO);
    }

    private function getBFType()
    {
        $prefixes = array(
            'PRO-',
            'HOSTING24-',
        );
        $bf_license = $this->getKey();
        foreach($prefixes as $prefix){
            if(substr($bf_license, 0, strlen($prefix)) == $prefix) {
                return \Fuse_Version::TYPE_PRO;
            }
        }

        return \Fuse_Version::TYPE_FREE;
    }

    public function getDetails($from_server = false)
    {
        $license = $this->getKey();
        $systemService = $this->di['mod_service']('system');
        $salt = 'IJ2bspsxk1U1INr';
        $key = md5('_lc_'.$license);
        $cache = $systemService->getParamValue($key);

        //set when last time was license checked
        $check_key = md5('_last_check_'.$license);
        $v = $systemService->getParamValue($check_key);

        $last_check = time();

        if(!$v) {
            $from_server = true;
        } else {
            $checkd = $this->di['crypt']->decrypt($v, $salt);
            if(is_numeric($checkd)) {
                $last_check = $checkd;
            }
        }

        if((time() - $last_check) > 60 * 60 * 1) {
            $from_server = true;
        }

        if($cache && !$from_server) {
            $data = @unserialize($this->di['crypt']->decrypt($cache, $salt));
            if ($data === false) {
                $from_server = true;
            }
        }

        if(!$cache || $from_server) {
            try {
                $servers = array(
                    'http://www.billingfuse.com/api/guest/servicelicense/check',
                );
                $l = $this->_getLicenseDetailsFromServer($servers);
            } catch(\LogicException $e) {
                error_log($e->getMessage());
                return array();
            }

            $data = $l;

            $systemService->setParamValue($key, $this->di['crypt']->encrypt(serialize($l), $salt), true);
            $systemService->setParamValue($check_key, $this->di['crypt']->encrypt(time(), $salt), true);
        } else {
            $data = @unserialize($this->di['crypt']->decrypt($cache, $salt));
            if (!is_array($data)) {
                error_log('Invalid response from licensing server. 9115');
                $systemService->setParamValue($key, $this->di['crypt']->encrypt(serialize(false), $salt), true);
            }
        }

        return $data;
    }

    public function _getLicenseDetailsFromServer(array $servers)
    {
        $params = array();
        $params['license']  = $this->di['config']['license'];
        $params['host']     = BF_URL;
        $params['path']     = BF_PATH_ROOT;
        $params['version']  = \Fuse_Version::VERSION;
        $params['os']       = PHP_OS;
        $params['format']   = 2;

        foreach($servers as $server) {
            $response = $this->_tryLicensingServer($server, $params);

            // check empty response
            if(!$response) {
                error_log($response);
                continue;
            }

            // check invalid json
            $r = json_decode($response, 1);
            if(!is_array($r) || !isset($r['result'])) {
                error_log('Invalid json result '. $response);
                continue;
            }

            // check server error
            if(isset($r['error']) && is_array($r['error'])) {
                error_log(print_r($r['error'], 1));
                continue;
            }

            if(!is_array($r['result']) || !isset($r['result']['valid'])) {
                error_log('Strange response from licensing server');
                continue;
            }

            if(!$r['result']['valid']) {
                $code = $r['result']['error_code'] ? (int)$r['result']['error_code'] : 9019;
                $msg = (string)$r['result']['error'];
                throw new \Exception($msg, $code);
            }

            return $r['result'];
        }

        return array();
    }

    private function _tryLicensingServer($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($ch);

        curl_close($ch);
        return $result;
    }
}