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

namespace Fuse\Mod\Filemanager;

class Service implements \Fuse\InjectionAwareInterface
{
    protected $di = null;

    public function setDi($di)
    {
        $this->di = $di;
    }

    public function getDi()
    {
        return $this->di;
    }

    /**
     * @param string $path
     * @param string $content
     */
    public function saveFile($path, $content = PHP_EOL)
    {
        $path  = $this->_getPath($path);
        $bytes = $this->di['tools']->file_put_contents($content, $path);

        return ($bytes > 0);
    }

    public function create($path, $type)
    {
        $path = $this->_getPath($path);
        $res  = false;
        switch ($type) {
            case 'dir':
                if (!$this->di['tools']->fileExists($path)) {
                    $res = $this->di['tools']->mkdir($path, 0755);
                } else {
                    throw new \Fuse_Exception('Directory already exists');
                }
                break;

            case 'file':
                $res = $this->saveFile($path, ' ');
                break;

            default:
                throw new \Fuse_Exception('Unknown item type');
        }
        return $res;
    }

    public function move($from, $to)
    {
        $from = $this->_getPath($from);
        $to   = $this->_getPath($to) . DIRECTORY_SEPARATOR . basename($from);
        return $this->di['tools']->rename($from, $to);
    }

    public function getFiles($dir = DIRECTORY_SEPARATOR)
    {
        $dir    = ($dir == DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : (string)$dir;
        $dir    = trim($dir, DIRECTORY_SEPARATOR);
        $dir    = $this->_getPath($dir);
        $getdir = realpath($dir);
        if (empty($getdir)) {
            return array(
                'filecount' => 0,
                'files'     => null,
            );
        }

        $sd = @scandir($getdir);
        $sd = array_diff($sd, array('.', '..', '.svn', '.git'));

        $files = $dirs = array();
        foreach ($sd as $file) {
            $path = $getdir . '/' . $file;
            if (is_file($path)) {
                $files[] = array('filename' => $file, 'type' => 'file', 'path' => $path, 'size' => filesize($path));
            } else {
                $dirs[] = array('filename' => $file, 'type' => 'dir', 'path' => $path, 'size' => filesize($path));
            }
        }
        $files            = array_merge($dirs, $files);
        $out              = array('files' => $files);
        $out['filecount'] = count($sd);

        return $out;
    }

    private function _getPath($path)
    {
        $_path = BF_PATH_ROOT . DIRECTORY_SEPARATOR;
        $path = str_replace($_path, '', $path);
        $path = trim($path, DIRECTORY_SEPARATOR);
        $path = str_replace('//', DIRECTORY_SEPARATOR, $_path . $path);

        return $path;
    }
} 