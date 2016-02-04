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


class Model_ClientPasswordResetTable implements \Fuse\InjectionAwareInterface
{
    /**
     * @var \Fuse__Di
     */
    protected $di;

    /**
     * @param Fuse__Di $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return Fuse__Di
     */
    public function getDi()
    {
        return $this->di;
    }

    public function generate(Model_Client $client, $ip)
    {
        $r = $this->di['db']->findOne('ClientPasswordReset', 'client_id', $client->id);
        if(!$r instanceof Model_ClientPasswordReset) {
            $r = $this->di['db']->dispense('ClientPasswordReset');
            $r->created_at  = date('Y-m-d H:i:s');
            $r->client_id   = $client->id;
        }
        
        $r->ip          = $ip;
        $r->hash        = sha1(rand(50, rand(10, 99)));
        $r->updated_at  = date('Y-m-d H:i:s');
        $this->di['db']->store($r);
        
        return $r;
    }
    
    public function rmByClient(Model_Client $client)
    {
        $models = $this->di['db']->find('ClientPasswordReset', 'client_id = ?', array($client->id));
        foreach($models as $model){
            $this->di['db']->trash($model);
        }
    }
    
}