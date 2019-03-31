<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/3/23
 * Time: 21:52
 * Site: http://www.drupai.com
 */

namespace App\Servers;


use App\Entity\User;
use App\Entity\WeChat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class WechatManager
{
    private $encoder;

    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager,UserPasswordEncoderInterface $encoder)
    {
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
    }

    public function createUser($username,$password = null,$role = 'ROLE_USER')
    {
        $user = new WeChat();
        $roles[] = $role;
        $user->setName($username)
            ->setPassword($this->updatePassword($user,$password))
            ->setRoles($roles)
        ;
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    public function updatePassword(User $user,$password)
    {
        return $this->encoder->encodePassword($user,$password);
    }

    public function changePassword($username,$password)
    {
        $user = $this->loadUserByName($username);
        $password = $this->updatePassword($user,$password);
        $user->setPassword($password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function loadUserByName($name): ?WeChat
    {
        return $this->entityManager->getRepository(WeChat::class)->findOneBy(['openid'=>$name]);
    }

}