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

class Fuse_Authorization
{
    private $di = null;
    private $session = null;

    public function __construct(Fuse_Di $di)
    {
        $this->di = $di;
        $this->session = $di['session'];
    }

    public function isClientLoggedIn()
    {
        return (bool)($this->session->get('client_id'));
    }

    public function isAdminLoggedIn()
    {
        return (bool)($this->session->get('admin'));
    }

    public function authorizeUser($user, $plainTextPassword)
    {
        $user = $this->passwordBackwardCompatibility($user, $plainTextPassword);
        if ($this->di['password']->verify($plainTextPassword, $user->pass)){
            if ($this->di['password']->needsRehash($user->pass)){
                $user->pass = $this->di['password']->hashIt($plainTextPassword);
                $this->di['db']->store($user);
            }
            return $user;
        }
        return null;
    }

    public function passwordBackwardCompatibility($user, $plainTextPassword)
    {
        if (sha1($plainTextPassword) == $user->pass){
            $user->pass = $this->di['password']->hashIt($plainTextPassword);
            $this->di['db']->store($user);
        }
        return $user;
    }

}
