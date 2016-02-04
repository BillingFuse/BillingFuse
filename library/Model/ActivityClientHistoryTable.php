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


class Model_ActivityClientHistoryTable implements \Fuse\InjectionAwareInterface
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

    /**
     * @param array $data
     */
    public function logEvent($data)
    {
        if(!isset($data['client_id']) || !isset($data['ip'])) {
            return ;
        }

        $entry = $this->di['db']->dispense('ActivityClientHistory');
        $entry->client_id       = $data['client_id'];
        $entry->ip              = $data['ip'];
        $entry->created_at      = date('Y-m-d H:i:s');
        $entry->updated_at      = date('Y-m-d H:i:s');
        $this->di['db']->store($entry);
    }

    public function rmByClient(Model_Client $client)
    {
        $models = $this->di['db']->find('ActivityClientHistory', 'client_id = ?', array($client->id));
        foreach($models as $model){
            $this->di['db']->trash($model);
        }
    }

}