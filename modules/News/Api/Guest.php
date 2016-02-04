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
 * News and announcements management
 */

namespace Fuse\Mod\News\Api;

class Guest extends \Api_Abstract
{
    /**
     * Get paginated list of active news items
     *
     * @return array
     */
    public function get_list($data)
    {
        $data['status'] = 'active';
        list ($sql, $params) = $this->getService()->getSearchQuery($data);
        $per_page = $this->di['array_get']($data, 'per_page', $this->di['pager']->getPer_page());
        $page = $this->di['array_get']($data, 'page');
        $pager = $this->di['pager']->getSimpleResultSet($sql, $params, $per_page, $page);
        foreach ($pager['list'] as $key => $item) {
            $post               = $this->di['db']->getExistingModelById('Post', $item['id'], 'Post not found');
            $pager['list'][$key] = $this->getService()->toApiArray($post);
        }

        return $pager;
    }

    /**
     * Get news item by ID or SLUG
     *
     * @param int $id - news item ID. Required only if SLUG is not passed.
     * @param string $slug - news item slug. Required only if ID is not passed.
     *
     * @return array
     */
    public function get($data)
    {
        if(!isset($data['id']) && !isset($data['slug'])) {
            throw new \Fuse_Exception('ID or slug is missing');
        }

        $id = $this->di['array_get']($data, 'id', NULL);
        $slug = $this->di['array_get']($data, 'slug', NULL);

        if($id) {
            $model = $this->getService()->findOneActiveById($id);
        } else {
            $model = $this->getService()->findOneActiveBySlug($slug);
        }

        if(!$model || $model->status !== 'active') {
            throw new \Fuse_Exception('News item not found');
        }
        return $this->getService()->toApiArray($model);
    }
}