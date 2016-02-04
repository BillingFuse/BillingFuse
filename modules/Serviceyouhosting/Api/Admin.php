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

class Admin extends \Api_Abstract
{
    /**
     * Synchronize order with YouHosting account details
     *
     * @param int $order_id - Order id
     *
     * @return boolean
     *
     * @throws \Exception
     */
    public function sync($data)
    {
        if (!isset($data['order_id'])) {
            throw new \Exception('order_id is missing');
        }

        $this->getService()->sync($data['order_id']);

        $this->di['logger']->info('Synchronized YouHosting account with order %s', $data['order_id']);

        return true;
    }

    /**
     * Return plans
     *
     * @return array
     */
    public function get_plans($data)
    {
        $plans  = $this->getService()->getApi()->call('Settings.plans');
        $result = array();
        foreach ($plans as $plan) {
            $result[$plan['id']] = $plan['name'];
        }

        return $result;
    }

    /**
     * Get login to cpanel url
     */
    public function cpanel_url($data)
    {
        $order = $this->di['db']->getExistingModelById('ClientOrder', $data['order_id'], 'Order not found');
        $order = $this->di['mod_service']('order')->toApiArray($order);

        $params = array(
            'id' => $order['meta']['yh_account_id'],
        );

        return $this->getService()->getApi()->call('Account.getLoginUrl', $params);
    }

    /**
     * Import YouHosting accounts as BillingFuse orders
     *
     * Import clients before calling this action
     */
    public function import_accounts($data)
    {
        if (!isset($data['page'])) {
            throw new \Exception('Page parameter is missing');
        }

        $log      = array();
        $service  = $this->getService();
        $lastPage = $service->getLastClientAccountImportPage();
        $page     = ($lastPage > $data['page']) ? $lastPage : $data['page'];

        $periods = array('none' => null, 'monthly' => '1M', 'quarterly' => '3M', 'biannually' => '6M', 'annually' => '1Y', 'biennially' => '2Y', 'triennially' => '3Y');

        $items = $service->getApi()->call('Account.getList', array('page' => $page, 'per_page' => 50, 'status' => 'active'));
        $pages = $items['pages'];

        //do import
        foreach ($items['list'] as $account) {

            if ($service->isYHAccountImported($account['id'])) {
                $log[] = 'Skipped already imported account #' . $account['id'];
                continue;
            }

            try {
                $id    = $service->importYouhostingAccount($account);
                $log[] = 'Imported order #' . $id . ' Youhosting account ' . $account['domain'];
            } catch (\Exception $e) {
                $log[] = $e->getMessage();
            }

        }

        $service->setLastClientAccountImportPage($page);

        return array(
            'log'      => implode(PHP_EOL, $log),
            'current'  => $page,
            'next'     => $page + 1,
            'total'    => $pages,
            'continue' => ($page <= $pages),
        );
    }

    /**
     * Import clients from YouHosting to BillingFuse
     *
     * @return array - log message
     *
     * @throws \Exception
     */
    public function import_clients($data)
    {
        if (!isset($data['page'])) {
            throw new \Exception('Page parameter is missing');
        }

        $log      = array();
        $service  = $this->getService();
        $lastPage = $service->getLastClientImportPage();
        $page     = ($lastPage > $data['page']) ? $lastPage : $data['page'];

        $clients = $this->getService()->getApi()->call('Client.getList', array('page' => $page, 'per_page' => 100));
        $pages   = $clients['pages'];

        foreach ($clients['list'] as $client) {
            try {
                $service->importYouhostingClient($client);
                $log[] = 'Imported client ' . $client['email'];
            } catch (\Exception $e) {
                $log[] = $e->getMessage();
            }
        }

        $service->setLastClientImportPage($page);

        return array(
            'log'      => implode(PHP_EOL, $log),
            'current'  => $page,
            'next'     => $page + 1,
            'total'    => $pages,
            'continue' => ($page <= $pages),
        );
    }
}

