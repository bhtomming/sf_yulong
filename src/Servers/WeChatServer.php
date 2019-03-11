<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/26
 * Time: 22:50
 * Site: http://www.drupai.com
 */

namespace App\Servers;


use App\Entity\WeChat;
use App\Entity\WechatConfig;
use Curl\Curl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class WeChatServer
{
    private $em;

    private $wechatConfig;

    private $wechat;

    private $curl;


    public function __construct(EntityManagerInterface $em,WeChat $wechat = null)
    {
        $this->em = $em;
        $this->wechatConfig = $this->getWeChatConfig();
        $this->wechat = $wechat;
        $this->curl = new Curl();
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

    //创建头像
    public function createFaceImg(WeChat $weChat)
    {

        return false;
    }

    //获取微信用户基本信息
    public function getUserInfo($openId)
    {
        $token = $this->getAccessToken();
        $userInfoUrl = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$openId.'&lang=zh_CN';
        $this->curl->get($userInfoUrl);
        $userInfo = json_decode($this->curl->getResponse());
        return $userInfo;
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
        return $this->em->getRepository(WeChat::class)->findOneBy(['openId'=>$openId]);
    }

    //用户关注的时候调用
    public function setSubscribe($openId)
    {
        $wechat = new WeChat();
        $wechat->setOpenid($openId);
        $wechat->setSubscribe(true);
        $userInfo = $this->getUserInfo($openId);
        $wechat
            ->setNickName($userInfo->nickname)
            ->setHeadImg($userInfo->headimgurl)
            ->setCity($userInfo->city)
            ->setSex($userInfo->sex)
            ->setProvicne($userInfo->province)
            ->setCountry($userInfo->country)
            ->setSubscribeTime($userInfo->subscribe_time)
            ;
    }

    public function listenToWechat(Request $request)
    {
        $response = $request->getContent();
        if(empty($response))
        {
            return;
        }
        $data = simplexml_load_string($request->getContent());
        $fromUsername = $data -> FromUserName;
        $msgType = $data -> MsgType;
        $toUsername = $data -> ToUserName;
        $keyword = trim($data -> Content);

        //判断是否是事件
        if($msgType == "event"){
            $event = $data->Event;

            //判断是否是关注事件
            if($event == "subscribe"){
                //获取扫推荐ID
                $sceneId = substr($data->EventKey,7);
                if(!empty($sceneId)){
                    //有推荐人的情况下
                }
            }
            //扫码事件
            if($event == "SCAN"){


            }
        }
    }



}