<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/9
 * Time: 17:27
 * Site: http://www.drupai.com
 */

namespace App\Security\Authentication\Provider;


use App\Contracts\WechatUserProvider;
use App\Security\Token\WechatUserToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class WechatProvider implements AuthenticationProviderInterface
{

    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * WechatProvider constructor.
     * @param WechatUserProvider $userProvider
     */
    public function __construct(WechatUserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @param TokenInterface $token
     * @return WechatUserToken
     * @throws UsernameNotFoundException
     */
    public function authenticate(TokenInterface $token)
    {
        /** @var WechatUserToken $token */
        $user = $this->userProvider->find($token->getOpenid());

        if (!$user) {
            throw new UsernameNotFoundException();
        }
        $token->setUser($user);

        return $token;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof WechatUserToken;
    }
}