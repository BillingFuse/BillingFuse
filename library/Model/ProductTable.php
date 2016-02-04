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


class Model_ProductTable implements \Fuse\InjectionAwareInterface
{
    const CUSTOM            = 'custom';
    const LICENSE           = 'license';
    const ADDON             = 'addon';
    const DOMAIN            = 'domain';
    const DOWNLOADABLE      = 'downloadable';
    const HOSTING           = 'hosting';
    const MEMBERSHIP        = 'membership';
    const VPS               = 'vps';

    const SETUP_AFTER_ORDER     = 'after_order';
    const SETUP_AFTER_PAYMENT   = 'after_payment';
    const SETUP_MANUAL          = 'manual';

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

    public function getUnit(Model_Product $model)
    {
        return $model->unit;
    }

    /**
     * @deprecated in order of Fuse__Period appear
     * @param Fuse__Period $period
     * @return string
     */
    private function _getPeriodKey(Fuse__Period $period)
    {
        $code = $period->getCode();
        switch ($code) {
            case '1W':
                return 'w';

            case '1M':
                return 'm';

            case '3M':
                return 'q';

            case '6M':
                return 'b';

            case '12M':
            case '1Y':
                return 'a';

            case '2Y':
                return 'bia';

            case '3Y':
                return 'tria';

            default:
                throw new Fuse__Exception('Unknown period selected ' . $code);
        }
    }

    public function getPricingArray(Model_Product $model)
    {
        if($model->product_payment_id) {
            $service = $this->di['mod_service']('product');
            $productPayment = $this->di['db']->load('ProductPayment', $model->product_payment_id);
            return $service->toProductPaymentApiArray($productPayment);
        }

        throw new \Fuse__Exception('Product pricing could not be determined. '.get_class($this));
    }

    /**
     * Price for one unit
     *
     * @param Model_Product $product
     * @param array $config
     * @return float
     */
    public function getProductSetupPrice(Model_Product $product, array $config = null)
    {
        $pp = $this->di['db']->load('ProductPayment', $product->product_payment_id);

        if($pp->type == Model_ProductPayment::FREE) {
            $price = 0;
        }

        if($pp->type == Model_ProductPayment::ONCE) {
            $price = (float)$pp->once_setup_price;
        }

        if($pp->type == Model_ProductPayment::RECURRENT) {
            $period = new Fuse__Period($config['period']);
            $key = $this->_getPeriodKey($period);
            $price = (float)$pp->{$key.'_setup_price'};
        }

        if(isset($price)) {
            return $price;
        }

        throw new \Fuse__Exception('Unknown Period selected for setup price');
    }

    /**
     * Price for one unit
     *
     * @param Model_Product $product
     * @param array $config
     * @return float
     */
    public function getProductPrice(Model_Product $product, array $config = null)
    {
        $pp = $this->di['db']->load('ProductPayment', $product->product_payment_id);

        if($pp->type == Model_ProductPayment::FREE) {
            $price = 0;
        }

        if($pp->type == Model_ProductPayment::ONCE) {
            $price = $pp->once_price;
        }

        if($pp->type == Model_ProductPayment::RECURRENT) {
            if(!isset($config['period'])) {
                throw new \Fuse__Exception('Product :id payment type is recurrent, but period was not selected', array(':id'=>$product->id));
            }

            $period = new Fuse__Period($config['period']);
            $key = $this->_getPeriodKey($period);
            $price =$pp->{$key.'_price'};
        }

        if(isset($price)) {
            return $price;
        }

        throw new \Fuse__Exception('Unknown Period selected for price');
    }
}