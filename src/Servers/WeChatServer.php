<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/26
 * Time: 22:50
 * Site: http://www.drupai.com
 */

namespace App\Servers;


use App\Entity\WechatConfig;
use Curl\Curl;
use Doctrine\ORM\EntityManagerInterface;

class WeChatServer
{
    private $em;

    private $wechatConfig;

    private $wechat;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->wechatConfig = $this->getWeChatConfig();
    }

    //获取微信配置信息
    public function getWeChatConfig(): ? WechatConfig
    {
        return $this->em->getRepository(WechatConfig::class)->find(1);
    }

    //注册会员信息
    public function register()
    {

    }

    //创建推荐二维码
    public function createQrcode()
    {

    }

    //获取微信用户基本信息
    public function getUserInfo()
    {

    }

    //获取微信用户带参数二维码
    public function getQrcode($scene_id)
    {
        $access_token = $this->getAccessToken();
        $qurl = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
        $qr_info = array(
            'action_name' => 'QR_LIMIT_SCENE',
            "action_info"=>array(
                "scene" => array(
                    "scene_id"=> $scene_id
                )
            )
        );
        $curl = new Curl();
        $curl->post($qurl,$qr_info);
        $wxResult = json_decode($curl->getResponse());
        $ticket = $wxResult->ticket;
        $curl->setHeader('Accept-Ranges','bytes');
        $curl->setHeader('Cache-control','max-age=604800');
        $curl->setHeader('Connection','keep-alive');
        $curl->setHeader('Content-Type','image/jpg');
        $qImgUrl = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;

        $imgPath = "qrcode/scene/{$scene_id}.jpg";
        $path = ROOT_PATH .$imgPath;
        $qrFile = fopen($path,'wb');
        $curl->setOpt(CURLFile,$qrFile);
        $curl->get($qImgUrl);
        $curl->setOpt(CURLFile,NULL);
        fclose($qrFile);
        return $imgPath;
    }

    //获取access_token
    public function getAccessToken()
    {
        return $this->wechatConfig->getAccessToken();
    }

    //创建微信菜单
    public function createMenu()
    {

    }

    //关键词回复
    public function keywordsRefund()
    {

    }

    //推荐成功给会员加分
    public function addPoints()
    {

    }

    //通过OpenId查找会员
    public function findMemberByOpenId($openId)
    {

    }



}