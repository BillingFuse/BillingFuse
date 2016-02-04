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
 *Emails history listing and management
 */

namespace Fuse\Mod\Email\Api;

class Client extends \Api_Abstract
{
    /**
     * Get list of emails system had sent to client
     * @return array - paginated list
     */
    public function get_list($data)
    {
        $client            = $this->getIdentity();
        $data['client_id'] = $client->id;
        $per_page          = $this->di['array_get']($data, 'per_page', $this->di['pager']->getPer_page());
        list($sql, $params) = $this->getService()->getSearchQuery($data);
        $pager = $this->di['pager']->getSimpleResultSet($sql, $params, $per_page);

        foreach ($pager['list'] as $key => $item) {
            $pager['list'][$key] = array(
                'id'           => $item['id'],
                'client_id'    => $item['client_id'],
                'sender'       => $item['sender'],
                'recipients'   => $item['recipients'],
                'subject'      => $item['subject'],
                'content_html' => $item['content_html'],
                'content_text' => $item['content_text'],
                'created_at'   => $item['created_at'],
                'updated_at'   => $item['updated_at'],
            );
        }

        return $pager;
    }

    /**
     * Get email details
     * @param int $id - Email id
     * @return array
     * @throws Exception
     * @throws LogicException
     */
    public function get($data)
    {
        $required = array(
            'id'         => 'Email ID is required',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        $model = $this->getService()->findOneForClientById($this->getIdentity(), $data['id']);

        if (!$model instanceof \Model_ActivityClientEmail) {
            throw new \Fuse_Exception('Email not found');
        }

        return $this->getService()->toApiArray($model);
    }

    /**
     * Resend email to client once again
     * 
     * @param int $id - Email id
     * @return type
     * @throws Exception
     * @throws LogicException 
     */
    public function resend($data)
    {
        $required = array(
            'id'         => 'Email ID is required',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        $model = $this->getService()->findOneForClientById($this->getIdentity(), $data['id']);
        if(!$model instanceof \Model_ActivityClientEmail) {
            throw new \Fuse_Exception('Email not found');
        }

        return $this->getService()->resend($model);
    }

    /**
     * Remove email from system.
     * @param int $id - Email id
     * @return type
     * @throws Exception
     * @throws LogicException
     */
    public function delete($data)
    {
        $required = array(
            'id'         => 'Email ID is required',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        $model = $this->getService()->findOneForClientById($this->getIdentity(), $data['id']);
        if (!$model instanceof \Model_ActivityClientEmail) {
            throw new \Fuse_Exception('Email not found');
        }

        return $this->getService()->rm($model);
    }
}