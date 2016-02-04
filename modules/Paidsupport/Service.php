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

/**
 * This file is a delegate for module. Class does not extend any other class
 *
 * All methods provided in this example are optional, but function names are
 * still reserved.
 *
 */

namespace Fuse\Mod\Paidsupport;

use Fuse\InjectionAwareInterface;

class Service implements InjectionAwareInterface
{
    /**
     * @var \Fuse_Di
     */
    protected $di;

    /**
     * @param \Fuse_Di $di
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

    public static function onBeforeClientOpenTicket(\Fuse_Event $event)
    {
        $di = $event->getDi();
        $params = $event->getParameters();

        $client = $di['db']->load('Client', $params['client_id']);

        $paidSupportService = $di['mod_service']('Paidsupport');
        $paidSupportService->setDi($di);
        if ($paidSupportService->hasHelpdeskPaidSupport($params['support_helpdesk_id'])) {
            $paidSupportService->enoughInBalanceToOpenTicket($client);
        }
        return true;
    }

    /**
     * @param \Fuse_Event $event
     * @return bool
     */
    public static function onAfterClientOpenTicket(\Fuse_Event $event)
    {
        $di = $event->getDi();
        $params = $event->getParameters();

        $supportTicket = $di['db']->load('SupportTicket', $params['id']);
        $client = $di['db']->load('Client', $supportTicket->client_id);

        $paidSupportService = $di['mod_service']('Paidsupport');
        $paidSupportService->setDi($di);
        if (!$paidSupportService->hasHelpdeskPaidSupport($supportTicket->support_helpdesk_id)) {
            return true;
        }

        $paidSupportService->enoughInBalanceToOpenTicket($client);

        $clientBalanceService = $di['mod_service']('Client', 'Balance');
        $clientBalanceService->setDi($di);

        $message = sprintf('Paid support ticket#%d "%s" opened', $supportTicket->id, $supportTicket->subject);
        $extra = array(
            'rel_id' => $supportTicket->id,
            'type' => 'supportticket',
        );
        $clientBalanceService->deductFunds($client, $paidSupportService->getTicketPrice(), $message, $extra);

        return true;
    }

    /**
     * @return float
     */
    public function getTicketPrice()
    {
        $config = $this->di['mod_config']('Paidsupport');
        if (!isset($config['ticket_price'])){
            error_log('Paid Support ticket price is not set');
            return (float) 0;
        }
        return (float) $config['ticket_price'];
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        $config = $this->di['mod_config']('Paidsupport');
        $errorMessage = $this->di['array_get']($config, 'error_msg', '');
        return strlen(trim($errorMessage)) > 0 ? $errorMessage : 'Configure paid support module!';
    }

    public function getPaidHelpdeskConfig()
    {
        $config = $this->di['mod_config']('Paidsupport');
        return isset($config['helpdesk']) ? $config['helpdesk'] : array();
    }

    public function enoughInBalanceToOpenTicket(\Model_Client $client)
    {
        $clientBalanceService = $this->di['mod_service']('Client', 'Balance');
        $clientBalance = $clientBalanceService->getClientBalance($client);

        if ($this->getTicketPrice() > $clientBalance){
            throw new \Fuse_Exception($this->getErrorMessage());
        }

        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function hasHelpdeskPaidSupport($id)
    {
        $helpdeskConfig = $this->getPaidHelpdeskConfig();

        if (isset($helpdeskConfig[$id]) && $helpdeskConfig[$id] == 1){
            return true;
        }
        return false;
    }

    public function uninstall()
    {
        $model = $this->di['db']->findOne('ExtensionMeta', 'extension = :ext AND meta_key = :key',
            array(':ext'=>'mod_paidsupport', ':key'=>'config'));
        if ($model instanceof \Model_ExtensionMeta) {
            $this->di['db']->trash($model);
        }
        return true;
    }

    public function install()
    {
        $extensionService = $this->di['mod_service']('Extension');
        $defaultConfig = array(
            'ext' => 'mod_paidsupport',
            'error_msg' => 'Insufficient funds'
        );
        $extensionService->setConfig($defaultConfig);
        return true;
    }

}