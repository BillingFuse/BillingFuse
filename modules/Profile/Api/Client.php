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
 *Client profile management
 */

namespace Fuse\Mod\Profile\Api;

class Client extends \Api_Abstract
{
    /**
     * Get currently logged in client details
     */
    public function get()
    {
        $clientService = $this->di['mod_service']('client');
        return $clientService->toApiArray($this->getIdentity(), true, $this->getIdentity());
    }

    /**
     * Update currencty logged in client details
     * 
     * @optional string $email - new client email. Must not exist on system
     * @optional string $last_name - last name
     * @optional string $aid - Alternative id. Usually used by import tools.
     * @optional string $gender - Gender - values: male|female
     * @optional string $country - Country
     * @optional string $city - city
     * @optional string $birthday - Birthday
     * @optional string $company - Company
     * @optional string $company_vat - Company VAT number
     * @optional string $company_number - Company number
     * @optional string $type - Identifies client type: company or individual
     * @optional string $address_1 - Address line 1
     * @optional string $address_2 - Address line 2
     * @optional string $postcode - zip or postcode
     * @optional string $state - country state
     * @optional string $phone - Phone number
     * @optional string $phone_cc - Phone country code
     * @optional string $document_type - Related document type, ie: passport, driving license
     * @optional string $document_nr - Related document number, ie: passport number: LC45698122
     * @optional string $notes - Notes about client. Visible for admin only
     * @optional string $lang - language option
     * @optional string $custom_1 - Custom field 1
     * @optional string $custom_2 - Custom field 2
     * @optional string $custom_3 - Custom field 3
     * @optional string $custom_4 - Custom field 4
     * @optional string $custom_5 - Custom field 5
     * @optional string $custom_6 - Custom field 6
     * @optional string $custom_7 - Custom field 7
     * @optional string $custom_8 - Custom field 8
     * @optional string $custom_9 - Custom field 9
     * @optional string $custom_10 - Custom field 10
     * 
     * @return boolean
     * @throws Exception 
     */
    public function update($data)
    {
        return $this->getService()->updateClient($this->getIdentity(), $data);
    }
    
    /**
     * Retrieve current API key
     */
    public function api_key_get($data)
    {
        $client = $this->getIdentity();
        return $client->api_token;
    }
    
    /**
     * Generate new API key
     */
    public function api_key_reset($data)
    {
        return $this->getService()->resetApiKey($this->getIdentity());
    }

    /**
     * Change client area password
     */
    public function change_password($data)
    {
        $required = array(
            'password'         => 'Password required',
            'password_confirm' => 'Password confirmation required',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        if ($data['password'] != $data['password_confirm']) {
            throw new \Fuse_Exception('Passwords do not match.');
        }

        return $this->getService()->changeClientPassword($this->getIdentity(), $data['password']);
    }

    /**
     * Clear session and logout
     * @return boolean 
     */
    public function logout()
    {
        return $this->getService()->logoutClient();
    }
}