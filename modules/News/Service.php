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


namespace Fuse\Mod\News;

class Service
{
    protected $di;

    /**
     * @param mixed $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    public function findOneActiveById($id)
    {
        return $this->di['db']->findOne('Post', 'id = :id AND status = "active"', array('id'=>$id));
    }
    
    public function findOneActiveBySlug($slug)
    {
        return $this->di['db']->findOne('Post', 'slug = :slug AND status = "active"', array('slug'=>$slug));
    }

    public function getSearchQuery($data)
    {
        $sql='SELECT *
            FROM post
            WHERE 1 ';

        $params = array();

        $search = $this->di['array_get']($data, 'search', NULL);
        $status = $this->di['array_get']($data, 'status', NULL);

        if(NULL !== $status) {
            $sql .= ' AND status = :status';
            $params['status'] = $status;
        }

        if(NULL !== $search) {
            $sql .= ' AND (m.title LIKE %:search% OR m.content LIKE %:search%)';
            $params['search'] = $search;
        }

        $sql .= ' ORDER BY created_at DESC';

        return array($sql, $params);
    }

    public function toApiArray($row, $role = 'guest', $deep = true)
    {
        $admin = $this->di['db']->getRow('SELECT name, email FROM admin WHERE id=:id', array('id' => $row->admin_id));

        $pos     = strpos($row->content, '<!--more-->');
        $excerpt = ($pos) ? substr($row->content, 0, $pos) : null;

        $data = array(
            'id'           => $row->id,
            'title'        => $row->title,
            'content'      => $row->content,
            'slug'         => $row->slug,
            'image'        => $row->image,
            'section'      => $row->section,
            'publish_at'   => $row->publish_at,
            'published_at' => $row->published_at,
            'expires_at'   => $row->expires_at,
            'created_at'   => $row->created_at,
            'updated_at'   => $row->updated_at,
            'author'       => array(
                'name'  => $admin['name'],
                'email' => $admin['email'],
            ),
            'excerpt'      => $excerpt,
        );

        if ($role == 'admin') {
            $data['status'] = $row->status;
        }

        return $data;
    }
}