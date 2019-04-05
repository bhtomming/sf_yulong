<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/5
 * Time: 22:20
 * Site: http://www.drupai.com
 */

namespace App\Security\Token;


use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class WechatUserToken extends AbstractToken
{

    public function __construct($openid, array $roles = [])
    {
        parent::__construct($roles);
        $this->setAttribute('openid',$openid);
        $this->setAuthenticated(count($roles) > 0);
    }

    /**
     * Returns the user credentials.
     *
     * @return mixed The user credentials
     */
    public function getCredentials()
    {
        return '';
    }

    public function getOpenid()
    {
        return $this->getAttribute("openid");
    }
}