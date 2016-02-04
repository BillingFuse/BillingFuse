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

class Fuse_Translate implements \Fuse\InjectionAwareInterface
{
    /**
     * @var \Fuse_Di
     */
    protected $di = NULL;

    protected $domain = 'messages';

    protected $locale = 'en_US';

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return Fuse_Translate
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @param Fuse_Di $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return Fuse_Di
     */
    public function getDi()
    {
        return $this->di;
    }

    public function setup()
    {
        $locale = $this->getLocale();
        $codeset = "UTF-8";
        if(!function_exists('gettext')) {
            require_once BF_PATH_LIBRARY . '/php-gettext/gettext.inc';
            T_setlocale(LC_MESSAGES, $locale.'.'.$codeset);
            T_setlocale(LC_TIME, $locale.'.'.$codeset);
            T_bindtextdomain($this->domain, BF_PATH_LANGS);
            T_bind_textdomain_codeset($this->domain, $codeset);
            T_textdomain($this->domain);
        } else {
            @putenv('LANG='.$locale.'.'.$codeset);
            @putenv('LANGUAGE='.$locale.'.'.$codeset);
            // set locale
            if (!defined('LC_MESSAGES')) define('LC_MESSAGES', 5);
            if (!defined('LC_TIME')) define('LC_TIME', 2);
            setlocale(LC_MESSAGES, $locale.'.'.$codeset);
            setlocale(LC_TIME, $locale.'.'.$codeset);
            bindtextdomain($this->domain, BF_PATH_LANGS);
            if(function_exists('bind_textdomain_codeset')) bind_textdomain_codeset($this->domain, $codeset);
            textdomain($this->domain);

            if (!function_exists('__')) {
                function __($msgid, array $values = NULL)
                {
                    if (empty($msgid)) return null;
                    $string = gettext($msgid);
                    return empty($values) ? $string : strtr($string, $values);
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param $domain
     * @return Fuse_Translate
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    public function __($msgid, array $values = NULL)
    {
        return __($msgid, $values);
    }
}