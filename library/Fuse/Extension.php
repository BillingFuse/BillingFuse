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
     * BillingFuse extensions API wrapper
     */
    class Fuse_Extension
    {

        /**
         * @var \Fuse_Ii
         */
        protected $di = null;

        /**
         * @param \Fuse_Ii $di
         */
        public function setDi($di)
        {
            $this->di = $di;
        }

        /**
         * @return \Fuse_Ii
         */
        public function getDi()
        {
            return $this->di;
        }

        const TYPE_MOD      = 'mod';
        const TYPE_THEME    = 'theme';
        const TYPE_PG       = 'payment-gateway';
        const TYPE_SM       = 'server-manager';
        const TYPE_DR       = 'domain-registrar';
        const TYPE_HOOK     = 'hook';
        const TYPE_TRANSLATION    = 'translation';

        private $_url = 'https://extensions.billingfuse.com/api/';

        public function getExtension($id, $type = Fuse_Extension::TYPE_MOD)
        {
            $params = array();
            $params['return'] = 'manifest';
            $params['type'] = $type;
            $params['id'] = $id;
            return $this->_request('guest/extension/get', $params);
        }

        public function getLatestExtensionVersion($id, $type = Fuse_Extension::TYPE_MOD)
        {
            $params = array();
            $params['type'] = $type;
            $params['id'] = $id;
            return $this->_request('guest/extension/version', $params);
        }

        public function getLatest($type = null)
        {
            $params = array();
            $params['return'] = 'manifest';
            if(!empty($type)) {
                $params['type'] = $type;
            }
            return $this->_request('guest/extension/search', $params);
        }

        /**
         * @param string $call
         */
        private function _request($call, array $params)
        {
            $params['bf_version'] = Fuse_Version::VERSION;
            $url = $this->_url.$call.'?'.http_build_query($params);
            $curl = new Fuse_Curl($url, 5);
            $curl->request();
            $response = $curl->getBody();
            $json = json_decode($response, 1);

            if(is_null($json)) {
                throw new \Fuse_Exception('Unable to connect to BillingFuse extensions site.', null, 1545);
            }

            if(isset($json['error']) && is_array($json['error'])) {
                throw new Exception($json['error']['message'], 746);
            }
            return $json['result'];
        }
    }