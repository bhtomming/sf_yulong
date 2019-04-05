<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/5
 * Time: 23:12
 * Site: http://www.drupai.com
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;

class AuthorizeEvent extends Event
{
    private $user;

    public function __construct(array $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

}