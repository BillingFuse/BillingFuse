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


class Model_ApiRequestTable implements \Fuse\InjectionAwareInterface
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

    public function logRequest($request, $ip)
    {
        $r = $this->di['db']->dispense('ApiRequest');
        $r->ip = $ip;
        $r->request = $request;
        $r->created_at = date('Y-m-d H:i:s');
        $this->di['db']->store($r);
    }

    public function getRequestCount($since, $ip = null)
    {
        $sinceIso = date('Y-m-d H:i:s', $since);

        $sql = 'SELECT count(id) as cc
                WHERE created_at > :since';

        $params = array(':since' => $sinceIso);

        if(NULL !== $ip) {
            $sql .= ' AND ip = :ip';
            $params[':ip'] = $ip;
        }

        $stmt = $this->di['pdo']->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn($stmt);
    }
}