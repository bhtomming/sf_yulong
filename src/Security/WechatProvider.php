<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/3/24
 * Time: 16:47
 * Site: http://www.drupai.com
 */

namespace App\Security;


use App\Contracts\WechatUserProvider;
use App\Security\Token\WechatUserToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;



class WechatProvider implements AuthenticationProviderInterface
{
    private $userProvider;

    public function __construct(WechatUserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }



    public function authenticate(TokenInterface $token): WechatUserToken
    {
        $user = $this->userProvider->find($token->getAttribute('openid'));
        if(!$user)
        {
            throw new UsernameNotFoundException("找不到用户名");
        }
        $token->setUser($user);

        return $token;
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @return bool true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof WechatUserToken;
    }
}