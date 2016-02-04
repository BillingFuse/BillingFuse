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
 * Youhosting service management
 */
namespace Fuse\Mod\Serviceyouhosting\Api;

class Guest extends \Api_Abstract
{
    /**
     * Youhosting webhooks listener
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function webhook($data)
    {
        $this->di['logger']->info('YouHosting WebHook: ' . print_r($data, 1));

        $config = $this->di['mod_config']('serviceyouhosting');

        if (isset($config['yh_ips'])) {
            $allowed_ips = explode(PHP_EOL, $config['yh_ips']);
            array_walk($allowed_ips, create_function('&$val', '$val = trim($val);'));
        } else {
            $allowed_ips = array(
                '212.1.209.2',
            );
        }

        if (!in_array($this->di['request']->getClientAddress(), $allowed_ips)) {
            throw new \Exception('IP not allowed');
        }

        if (!isset($data['yh_event'])) {
            throw new \Exception('Event name is missing');
        }

        $account_id = $this->di['array_get']($data, 'yh_account_id', null);
        $client_id  = $this->di['array_get']($data, 'yh_client_id', null);

        $service      = $this->getService();
        $orderService = $this->di['mod_service']('order');

        switch ($data['yh_event']) {
            case 'CREATE_ACCOUNT':
                $bf_order_id = $service->isYHAccountImported($account_id);
                if (!$bf_order_id) {
                    $account = $service->getApi()->call('Account.get', array('id' => $account_id));
                    $service->importYouhostingAccount($account);

                    $this->di['logger']->info('Imported YouHosting account. Invoked by WebHook');
                }

                break;

            case 'SUSPEND_ACCOUNT':
                $bf_order_id = $service->isYHAccountImported($account_id);
                $order       = $this->di['db']->getExistingModelById('ClientOrder', $data['order_id'], 'Order not found');
                if ($order->status == 'active') {
                    $orderService->suspendFromOrder($order, 'Auto-Suspend');
                    $this->di['logger']->info('Suspended YouHosting account. Invoked by WebHook');
                }
                break;

            case 'UNSUSPEND_ACCOUNT':
                $bf_order_id = $service->isYHAccountImported($account_id);
                $order       = $this->di['db']->getExistingModelById('ClientOrder', $data['order_id'], 'Order not found');
                if ($order->status == 'suspended') {
                    $orderService->unsuspendFromOrder($order);
                    $this->di['logger']->info('Unsuspended YouHosting account. Invoked by WebHook');
                }
                break;

            case 'REMOVE_ACCOUNT':
                $bf_order_id = $service->isYHAccountImported($account_id);
                $order       = $this->di['db']->getExistingModelById('ClientOrder', $data['order_id'], 'Order not found');
                if ($order->status == 'active') {
                    $orderService->cancelFromOrder($order, 'Auto-Suspend');
                    $this->di['logger']->info('Canceled YouHosting account. Invoked by WebHook');
                }
                break;

            default:
                break;
        }

        return true;
    }

    /**
     * Return master_domains
     *
     * @return array
     */
    public function master_domains($data)
    {
        $key = 'settings_subdomains';

        $cache = $this->di['cache'];
        if ($cache->exists($key)) {
            return $cache->get($key);
        }

        try {
            $result = $this->getService()->getApi()->call('Settings.subdomains');
            $cache->set($key, $result);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $result = array();
        }

        return $result;
    }
}