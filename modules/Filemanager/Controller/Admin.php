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


namespace Fuse\Mod\Filemanager\Controller;

class Admin implements \Fuse\InjectionAwareInterface
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

    public function fetchNavigation()
    {
        return array(
            'subpages'=>array(
                array(
                    'location'  => 'extensions',
                    'index'     => 5000,
                    'label' => 'File editor',
                    'uri'   => $this->di['url']->adminLink('filemanager'),
                    'class' => '',
                ),
            ),
        );
    }

    public function register(\Fuse_App &$app)
    {
        $app->get('/filemanager',              'get_index', array(), get_class($this));
        $app->get('/filemanager/ide',              'get_ide', array(), get_class($this));
        $app->get('/filemanager/editor',    'get_editor', array(), get_class($this));
        $app->get('/filemanager/icons',        'get_icons', array(), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_filemanager_index');
    }
    
    public function get_ide(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        $dir = BF_PATH_ROOT . DIRECTORY_SEPARATOR;
        $data = array('dir'=>$dir);
        if(isset($_GET['inline'])) {
            $data['show_full_screen'] = true;
        }
        if(isset($_GET['open'])) {
            $data['open'] = $_GET['open'];
        }
        return $app->render('mod_filemanager_ide', $data);
    }
    
    public function get_editor(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        
        $file = $_GET['file'];
        if(!$file || !$this->di['tools']->fileExists($file)) {
            throw new \Fuse_Exception('File does not exist', null, 404);
        }
        
        // check if file is from BillingFuse folder
        $p = substr($file, 0, strlen(BF_PATH_ROOT));
        if($p != BF_PATH_ROOT) {
            throw new \Fuse_Exception('File does not exist', null, 405);
        }
        
        $type = 'file';
        $info = pathinfo($file);
        switch(strtolower($info['extension'])){
            case 'jpeg':
            case 'jpg':
            case 'jpe':
            case 'tif':
            case 'tiff':
            case 'xbm':
            case 'png':
            case 'gif':
            case 'ico':
                $type = 'image';
                break;
            case 'htm':
            case 'html':
            case 'shtml':
            case 'phtml':
            case 'twig':
                $js = "mode-html.js";
                $mode = "ace/mode/html";		
                break;			
            case 'js':
                $js = "mode-javascript.js";
                $mode = "ace/mode/javascript";		
                break;			
            case 'css':
                $js = "mode-css.js";
                $mode = "ace/mode/css";		
                break;			
            case 'php':
                $js = "mode-php.js";
                $mode = "ace/mode/php";		
                break;			
            case 'json':
                $js = "mode-json.js";
                $mode = "ace/mode/json";		
                break;			
            case 'pl':
            case 'pm':
                $js = "mode-pearl.js";
                $mode = "ace/mode/php";		
                break;			
            default:
                $js = "mode-html.js";
                $mode = "ace/mode/html";
                break;
        }
        if($type == 'file') {
            $content = $this->di['tools']->file_get_contents($file);
            $d = array(
                'info'      => $info,
                'file'      => $file,
                'file_content'=>htmlentities($content), 
                'js'        =>$js, 
                'mode'      =>$mode
            );
            return $app->render('mod_filemanager_editor', $d);
        } else {
            $d = array(
                'info'      => $info,
                'file'      => $file,
                'src'       => BF_URL . substr($file, strlen($p)),
            );
            return $app->render('mod_filemanager_image', $d);
        }
    }
    
    public function get_icons(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        $location = BF_PATH_UPLOADS.'/icons/*';
        $list = array();
        $files = glob($location);
        foreach($files as $f) {
            $name = pathinfo($f, PATHINFO_BASENAME);
            $list[] =  $this->di['config']['url'].'/uploads/icons/'.$name;
        }
        
        return $app->render('mod_filemanager_icons', array('icons'=>$list));
    }
}