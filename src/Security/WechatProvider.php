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
use App\Entity\User;
use App\Entity\WeChat;
use App\Security\Token\WechatUserToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class WechatProvider implements UserProviderInterface
{
    private $entityManager;



    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
        $user = $this->findOneUserBy(['openid'=>$username]);
        if(!$user){
            throw new UsernameNotFoundException(
                sprintf(
                    'User with "%s" username does not exist.',
                    $username
                )
            );
        }
        return $user;
    }

    /**
     * Refreshes the user.
     *
     * It is up to the implementation to decide if the user data should be
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
            //dump($user->getPassword());exit;
            return $user;
        }
        assert($user instanceof WeChat);


        if(null === $relodedUser = $this->findOneUserBy(['id' => $user->getId()])){
            throw new UsernameNotFoundException(
                sprintf(
                    'User with id %s is not be reload',
                    $user->getId()
                )
            );
        }


        return $relodedUser;
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

    public function findOneUserBy(array $options): ?WeChat
    {
        return $this->entityManager->getRepository(WeChat::class)->findOneBy($options);
    }
}