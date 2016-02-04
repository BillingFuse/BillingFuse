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

namespace Fuse\Mod\Seo;

use Fuse\InjectionAwareInterface;

class Service implements InjectionAwareInterface
{
    protected $di;

    /**
     * @param mixed $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return mixed
     */
    public function getDi()
    {
        return $this->di;
    }

    public function pingSitemap($config)
    {
        $systemService = $this->di['mod_service']('system');

        $key       = 'mod_seo_last_sitemap_submit';
        $last_time = $systemService->getParamValue($key);

        if ($last_time && (time() - strtotime($last_time)) < 86400) {
            return false;
        }

        $url = urldecode(BF_URL . 'sitemap.xml');
        if (isset($config['sitemap_google']) && $config['sitemap_google']) {
           try{
               $link = "http://www.google.com/webmasters/sitemaps/ping?sitemap=" . $url;
               $this->di['guzzle_client']->get($link);
               error_log('Submitted sitemap to Google');
           }catch (\Exception $e){
               error_log('Exception :(');
           }
        }

        if (isset($config['sitemap_bing']) && $config['sitemap_bing']) {
            $link = "http://www.bing.com/ping?sitemap=" . $url;
            $this->di['guzzle_client']->get($link);
            error_log('Submitted sitemap to Bing');
        }

        $systemService->updateParams(array($key => date('Y-m-d H:i:s')));

        return true;
    }

    public function pingRss($config)
    {
        //@todo
        return false;

        $rss      = '';
        $title    = '';
        $homepage = BF_URL;

        $rss      = urldecode($rss);
        $title    = urldecode($title);
        $homepage = urldecode($homepage);

        $fp = @fopen("http://rpc.weblogs.com/pingSiteForm?name=$title&url=" . $rss, "r");
        @fclose($fp);
        $fp = @fopen("http://pingomatic.com/ping/?title=$title&blogurl=$homepage&rssurl=" . $rss . "&chk_weblogscom=on&chk_blogs=on&chk_feedburner=on&chk_syndic8=on&chk_newsgator=on&chk_myyahoo=on&chk_pubsubcom=on&chk_blogdigger=on&chk_blogstreet=on&chk_moreover=on&chk_weblogalot=on&chk_icerocket=on&chk_newsisfree=on&chk_topicexchange=on&chk_google=on&chk_tailrank=on&chk_postrank=on&chk_skygrid=on&chk_collecta=on&chk_superfeedr=on&chk_audioweblogs=on&chk_rubhub=on&chk_geourl=on&chk_a2b=on&chk_blogshares=on", "r");
        @fclose($fp);

        return true;
    }

    public static function onBeforeAdminCronRun(\Fuse_Event $event)
    {
        $di = $event->getDi();
        $extensionService = $di['mod_service']('extension');
        $config = $extensionService->getConfig("mod_seo");

        try {
            $seoService = $di['mod_service']('seo');
            $seoService->setDi($di);
            $seoService->pingSitemap( $config);
            $seoService->pingRss($config);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return true;
    }
}