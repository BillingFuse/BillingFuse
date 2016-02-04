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

namespace Fuse\Mod\Servicedownloadable\Api;
/**
 * Downloadable service management
 */
class Admin extends \Api_Abstract
{
    /**
     * Upload file to product. Uses $_FILES array so make sure your form is
     * enctype="multipart/form-data"
     *
     * @param int $id - product id
     * @param file $file_data - <input type="file" name="file_data" /> field content
     *
     * @return bool
     */
    public function upload($data)
    {
        $required = array(
            'id' => 'Product ID is missing',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        $model = $this->di['db']->getExistingModelById('Product', $data['id'], 'Product not found');

        if(!isset($_FILES['file_data'])) {
            throw new \Fuse_Exception('File was not uploaded');
        }

        $service = $this->getService();
        return $service->uploadProductFile($model);
    }

    /**
     * Update downloadable product order with new file.
     * This will change only this order file.
     *
     * Uses $_FILES array so make sure your form is
     * enctype="multipart/form-data"
     *
     * @param int $order_id - order id
     * @param file $file_data - <input type="file" name="file_data" /> field content
     *
     * @return bool
     */
    public function update($data)
    {
        $required = array(
            'order_id' => 'Order ID is missing',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        $order = $this->di['db']->getExistingModelById('ClientOrder', $data['order_id'], 'Order not found');

        $orderService = $this->di['mod_service']('order');
        $serviceDownloadable = $orderService->getOrderService($order);
        if(!$serviceDownloadable instanceof \Model_ServiceDownloadable) {
            throw new \Fuse_Exception('Order is not activated');
        }

        $service = $this->getService();
        return $service->updateProductFile($serviceDownloadable, $order);
    }
}