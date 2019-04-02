<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/3/24
 * Time: 16:47
 * Site: http://www.drupai.com
 */

namespace App\Security;


use App\Entity\User;
use App\Entity\WeChat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class WechatProvider implements UserProviderInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        $user = $this->findOneBy(['openid' => $username]);
        if(!$user)
        {
            throw new UsernameNotFoundException(sprintf(
                "没有找到您的会员信息 %s",
                $username
            ));
        }
        if(!$user->getSubscribe())
        {
            throw new CustomUserMessageAuthenticationException("请先关注再登录");
        }

        return $user;
    }

    /**
     * Refreshes the user.
     *
     * It is up to the implementation to decide if the user images should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException  if the user is not supported
     * @throws UsernameNotFoundException if the user is not found
     */
    public function refreshUser(UserInterface $user)
    {

        if($user instanceof User)
        {
            return $user;
        }
        assert($user instanceof WeChat);

        if(null === $reloader = $this->findOneBy(['id'=>$user->getId()]))
        {
            throw new UsernameNotFoundException(sprintf(
                "找不到用户ID：%s",
                $user->getId()
            ));
        }

        return $reloader;
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === WeChat::class;
    }

    public function findOneBy(array $options): ?WeChat
    {
        return $this->em->getRepository(WeChat::class)->findOneBy($options);
    }
}