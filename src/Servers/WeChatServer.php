<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/26
 * Time: 22:50
 * Site: http://www.drupai.com
 */

namespace App\Servers;


use App\Entity\Member;
use App\Entity\User;
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

        $curWechat = $this->em->getRepository(WeChat::class)->findOneBy(['openId'=>$fromUsername]);
        if(!$curWechat instanceof WeChat){
            return;
        }

        $returnData['fromUser'] = $data -> ToUserName;

        //判断是否是事件，微信事件处理
        if($msgType == "event")
        {
            $event = $data->Event;

            //判断是否是关注事件
            if($event == "subscribe")
            {
                $user = new User();
                $newMember = new Member();
                $username = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 4, 8), 1))), 0, 5);
                $password = substr(md5(time()), 0, 8);
                $curWechat->setSubscribe(true);
                //获取扫推荐ID
                $sceneId = substr($data->EventKey,7);
                if(!empty($sceneId) && $curWechat->getUser() == null)
                {
                    //有推荐人的情况下
                    $member = $this->em->getRepository(Member::class)->find($sceneId);
                    if(!$member instanceof  Member)
                    {
                        return;
                    }
                    $parentWechat = $member->getUser()->getWechat();
                    if($curWechat->getOpenid() == $parentWechat->getOpenid()){
                        $returnData['content'] ="错误提示：推荐关系不合法可能情况1：自己不能成为自己的下级2：自己有下级后不能成为别人的下级";
                    }

                    $member->addPoints(1,"推荐下线成功");
                    $returnData['touser'] = $parentWechat->getOpenid();
                    $returnData['msgtype'] = 'news';
                    $returnData['news'] = array(
                        "articles" => array(
                            "title" =>"您有新朋友加入了，赶紧看看吧",
                            "description" =>"新朋友的消费您都将有积分哦",
                            "url" =>"http://bhyulong.cn/distribute.php",
                            "picurl" => "http://bhyulong.cn/images/bh_dichan_icon.png"
                        )
                    );
                    //向微信显示内容
                    $this->sendNews($returnData);
                    $shop_name = "玉泷商城";
                    $returnData['content'] = "恭喜您由".$parentWechat->getNickName()."推荐成为".$shop_name."的会员！点击左下角“".$shop_name."”立即购买成为".$shop_name."掌柜，裂变你的代理商，让你每天睡觉都能赚大钱！";
                }

                $user->setName($username)
                    ->setPassword($password)
                    ->setMember($newMember)
                    ->setWechat($curWechat)
                ;
                $userInfo = "您的账号：".$username."密码：".$password;
                $returnData['content'] .= $userInfo;
                $returnData['touser'] = $curWechat->getOpenid();
                //向微信返回信息
                $this->sendText($returnData);
            }
            //取消关注事件
            if($event == "unsubscribe"){
            }
            //用户已经关注时才会出现的扫码事件
            if($event == "SCAN"){
            }
        }
        elseif($msgType == "text")//文本消息处理
        {
        }
        elseif($msgType == "image")//图片消息处理
        {
        }
        elseif($msgType == "voice")//语音消息处理
        {
        }
        elseif($msgType == "video")//视频消息处理
        {
        }
        elseif($msgType == "location")//位置消息处理
        {
        }
        elseif($msgType == "link") //链接消息处理
        {
        }
    }

    //自动向用户发送文本消息
    public function sendText($data)
    {
        $textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><Content><![CDATA[%s]]></Content></xml>";
        sprintf($textTpl,$data['toUser'],$data['fromUser'],time(),$data['msgType'],$data['content']);
        echo $textTpl;
    }

    //向用户发送图文链接客服消息
    public function sendNews($data)
    {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
        $this->curl->post($url,json_encode($data));
        $res = $this->curl->getResponse();
        echo $res;
    }


}