<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/26
 * Time: 22:44
 * Site: http://www.drupai.com
 */

namespace App\Servers;


use App\Entity\Member;
use App\Entity\PointsConfig;
use App\Entity\PointsLog;
use App\Entity\User;
use App\Entity\WeChat;
use Doctrine\ORM\EntityManagerInterface;

class MemberManager
{
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //创建会员
    public function createMember(array $options)
    {
        $member = new Member();
        $user = new User();
        $wechat = new WeChat();
        $wechat->setOpenid($options['openId'])
                ->setNickName($options['nickName'])
                ->setHeadImg($options['headImg'])
                ->setCountry($options['country'])
                ->setProvicne($options['province'])
                ->setCity($options['city'])
                ->setSex($options['sex'])
                ->setQrcode($options['qrcode'])
        ;
        $user->setName($options['username'])
            ->setPassword($options['password'])
            ->setRoles($options['roles'])
            ->setWechat($wechat)
        ;
        $member->setParent($options['parent'])
            ->setUser($user)
        ;
        return $member;

    }

    //删除会员
    public function deleteMember()
    {

    }

    //更新会员信息
    public function updateMember()
    {

    }

    //分配积分
    public function distribute(Member $member,$points)
    {
        $pointsConfig = $this->getPointsConfig();
        $ownPoint = ($points * $pointsConfig->getGivePoint()) /100;
        $member->addPoints($ownPoint,'自己购买商品');
        if($member->hasParent()){
            $parent = $member->getParent();
            $parent->addPoints(($points * $pointsConfig->getDerectPoint()) /100 ,'下级购买商品');
            if($parent->hasParent()){
                $deparent = $parent->getParent();
                $deparent->addPoints(($points * $pointsConfig->getInderectPoint()) /100,'间接下级购买商品');
            }
        }
        $this->em->persist($member);
        $this->em->flush();
    }

    public function getPointsConfig(): PointsConfig
    {
        return  $this->em->getRepository(PointsConfig::class)->find(1);
    }





}