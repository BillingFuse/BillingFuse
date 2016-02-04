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

namespace Fuse\Mod\Theme\Api;
class Admin extends \Api_Abstract
{

    /**
     * Get list of available client area themes
     * 
     * @return array 
     */
    public function get_list($data)
    {
        $themes = $this->getService()->getThemes();
        return array('list'=>$themes);
    }

    /**
     * Get theme by code
     * 
     * @param string $code - theme code
     * 
     * @return array 
     */
    public function get($data)
    {
        $required = array(
            'code'    => 'Theme code is missing',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        return $this->getService()->loadTheme($data['code']);
    }

    /**
     * Set new theme as default
     * 
     * @param string $code - theme code
     * @return bool
     */
    public function select($data)
    {
        $required = array(
            'code'    => 'Theme code is missing',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        $theme = $this->getService()->getTheme($data['code']);

        $systemService = $this->di['mod_service']('system');
        if($theme->isAdminAreaTheme()) {
            $systemService->setParamValue('admin_theme', $data['code']);
        } else {
            $systemService->setParamValue('theme', $data['code']);
        }

        $this->di['logger']->info('Changed default theme');
        return true;
    }

    /**
     * Delete theme preset
     *
     * @param string $code - theme code
     * @param string $preset - theme preset code
     *
     * @return bool
     */
    public function preset_delete($data)
    {
        $required = array(
            'code'    => 'Theme code is missing',
            'preset'    => 'Theme preset name is missing',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        $service = $this->getService();

        $theme = $service->getTheme($data['code']);
        $service->deletePreset($theme, $data['preset']);

        return true;
    }

    /**
     * Select new theme preset
     *
     * @param string $code - theme code
     * @param string $preset - theme preset code
     *
     * @return bool
     */
    public function preset_select($data)
    {
        $required = array(
            'code'    => 'Theme code is missing',
            'preset'    => 'Theme preset name is missing',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        $service = $this->getService();
        $theme = $service->getTheme($data['code']);
        $service->setCurrentThemePreset($theme, $data['preset']);

        return true;
    }
}