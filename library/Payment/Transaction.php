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

class Payment_Transaction
{
    const STATUS_UNKNOWN        = 'unknown';
    const STATUS_PENDING        = 'pending';
    const STATUS_COMPLETE       = 'complete';

    const TXTYPE_PAYMENT        = 'payment';
    const TXTYPE_REFUND         = 'refund';
    const TXTYPE_SUBSCR_CREATE  = 'subscription_create';
    const TXTYPE_SUBSCR_CANCEL  = 'subscription_cancel';
    const TXTYPE_UNKNOWN        = 'unknown';

    private $id                 = NULL;
    private $type               = self::TXTYPE_UNKNOWN;
    private $status             = self::STATUS_UNKNOWN;
    private $currency           = NULL;
    private $amount             = NULL;
    private $subscription_id    = NULL;
    
    public function setId($param)
    {
        $this->id = $param;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setStatus($param)
    {
        $this->status = $param;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setType($param)
    {
        $this->type = $param;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setCurrency($param)
    {
        $this->currency = $param;
        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setAmount($param)
    {
        $this->amount = $param;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }
    
    public function setSubscriptionId($param)
    {
        $this->subscription_id = $param;
        return $this;
    }

    public function getSubscriptionId()
    {
        return $this->subscription_id;
    }
}