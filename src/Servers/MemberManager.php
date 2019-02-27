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
use App\Entity\User;
use App\Entity\WeChat;

class MemberManager
{
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





}