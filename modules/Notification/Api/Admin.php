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
 * Notifications center management.
 *
 * Notifications are important messages for staff messages to get informed
 * about important events on BillingFuse.
 *
 * For example cron job can inform staff members
 */

namespace Fuse\Mod\Notification\Api;

class Admin extends \Api_Abstract
{
    /**
     * Get paginated list of notifications
     *
     * @return array
     */
    public function get_list($data)
    {
        list($sql, $params) = $this->getService()->getSearchQuery($data);
        $per_page = $this->di['array_get']($data, 'per_page', $this->di['pager']->getPer_page());
        return $resultSet = $this->di['pager']->getSimpleResultSet($sql, $params, $per_page);
    }

    /**
     * Get notification message
     *
     * @param int $id - message id
     * @return array
     * @throws Fuse_Exception
     */
    public function get($data)
    {
        $required = array(
            'id' => 'Notification ID is missing',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        $meta = $this->di['db']->load('extension_meta', $data['id']);
        if ($meta->extension != 'mod_notification' || $meta->meta_key != 'message') {
            throw new \Fuse_Exception('Notification message was not found');
        }

        return $this->getService()->toApiArray($meta);
    }

    /**
     * Add new notification message
     *
     * @param string $message - message text
     * @return int|false - new message id
     */
    public function add($data)
    {
        if (!isset($data['message'])) {
            return false;
        }

        $message = htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8');

        return $this->getService()->create($message);
    }

    /**
     * Remove notification message
     *
     * @param int $id - message id
     * @return boolean
     * @throws Fuse_Exception
     */
    public function delete($data)
    {
        $required = array(
            'id' => 'Notification ID is missing',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        $meta = $this->di['db']->load('extension_meta', $data['id']);
        if ($meta->extension != 'mod_notification' || $meta->meta_key != 'message') {
            throw new \Fuse_Exception('Notification message was not found');
        }
        $this->di['db']->trash($meta);

        return true;
    }

    /**
     * Remove all notification messages
     *
     * @return boolean
     * @throws Fuse_Exception
     */
    public function delete_all()
    {
        $sql = "DELETE
            FROM extension_meta 
            WHERE extension = 'mod_notification'
            AND meta_key = 'message';";
        $this->di['db']->exec($sql);

        return true;
    }
}