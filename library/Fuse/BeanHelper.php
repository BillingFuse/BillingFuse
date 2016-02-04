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

class Fuse_BeanHelper extends \RedBeanPHP\BeanHelper\SimpleFacadeBeanHelper implements \Fuse\InjectionAwareInterface
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

    public function getModelForBean( \RedBeanPHP\OODBBean $bean )
    {
        $prefix    = '\\Model_';
        $model     = $bean->getMeta( 'type' );
        $modelName = $prefix.$this->underscoreToCamelCase($model);

        if ( !class_exists( $modelName ) ) {
            return null;
        }

        $model = new $modelName();
        if($model instanceof \Fuse\InjectionAwareInterface) {
            $model->setDi( $this->di );
        }

        $model->loadBean( $bean );

        return $model;
    }

    private function underscoreToCamelCase( $string, $first_char_caps = true)
    {
        if( $first_char_caps === true )
        {
            $string[0] = strtoupper($string[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $string);
    }
} 