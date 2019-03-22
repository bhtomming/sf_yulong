<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/26
 * Time: 22:50
 * Site: http://www.drupai.com
 */

namespace App\Servers;


use App\Entity\Goods;
use App\Entity\Member;
use App\Entity\User;
use App\Entity\WeChat;
use App\Entity\WechatConfig;
use Curl\Curl;
use Doctrine\ORM\EntityManagerInterface;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\OfficialAccount\Application;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class WeChatServer
{
    private $em;

    private $wechatConfig;

    private $fileSystem;

    private $curl;

    private $returnData;

    private $app;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->wechatConfig = $this->getWeChatConfig();
        $this->curl = new Curl();
        $this->fileSystem = new Filesystem();
        $this->app = $this->getApp();
    }

    public function getApp(): ? Application
    {
        $config = array(
            'app_id' => $this->wechatConfig->getAppid(),
            'secret' => $this->wechatConfig->getAppscret(),
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            'log' => [
                'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
                'channels' => [
                    // 测试环境
                    'dev' => [
                        'driver' => 'single',
                        'path' => 'tmp/easywechat.log',
                        'level' => 'debug',
                        'days' => 5,
                    ],
                    // 生产环境
                    'prod' => [
                        'driver' => 'daily',
                        'path' => 'tmp/easywechat.log',
                        'level' => 'info',
                        'days' => 5,
                    ],
                ],
            ],
        );
        return Factory::officialAccount($config);
    }


    //获取微信配置信息
    public function getWeChatConfig(): ? WechatConfig
    {
        return $this->em->getRepository(WechatConfig::class)->find(1);
    }

    //注册会员信息
    public function register($openId): ? WeChat
    {
        $user = new User();
        $newMember = new Member();
        $weChat = new WeChat();
        $weChat->setOpenid($openId);
        $userInfo = $this->getUserInfo($openId);
        $weChat
            ->setNickName($userInfo->nickname)
            ->setHeadImg($userInfo->headimgurl)
            ->setCity($userInfo->city)
            ->setSex($userInfo->sex)
            ->setProvicne($userInfo->province)
            ->setCountry($userInfo->country)
            ->setSubscribeTime($userInfo->subscribe_time)
        ;
        $username = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 4, 8), 1))), 0, 5);
        $password = substr(md5(time()), 0, 8);
        $userInfo = "您的账号：".$username."密码：".$password;
        $user->setName($username)
            ->setPassword($password)
            ->setMember($newMember)
            ->setWechat($weChat)
        ;
        $this->returnData['content'] .= $userInfo;
        $this->returnData['touser'] = $weChat->getOpenid();
        $this->em->persist($user);
        $this->em->flush();
        return $weChat;
    }

    //创建推荐二维码
    public function createQrcode(WeChat $wechat)
    {
        $qr_width = 255;  // 是二维码图片宽度
        $qr_height = 255; // 二维码图片高度
        $qr_x = 152;
        $qr_y = 380;
        $hearimg_width = 80;
        $hearimg_hight = 80;
        $hearimg_x = 80;
        $hearimg_y = 50;
        $bg_img = "data/qrcode/1540239067195492735.jpg"; //合成图片的背景
        $text_red = 255;
        $text_geren = 0;
        $text_blue = 0;

        //===================================================================//

        $fname = time().'jpg';

        $h_imgsrc = $this->createFaceImg($wechat,$fname);

        $time = substr($fname,0,-4);
        $qr_src = 'qrcode/scene/'.$wechat->getUser()->getId().".jpg";
        $qr_imgs= "images/qrcode/".$time.".jpg";
        //加载图片信息
        $target_qr =  imagecreatetruecolor($qr_width,$qr_height);
        $source_qr = imagecreatefromjpeg(ROOT_PATH.$qr_src);
        //调整二维码大小
        Imagecopyresized($target_qr, $source_qr, 0, 0, 23, 23, $qr_width, $qr_height, 383, 383);
        imagejpeg($target_qr,ROOT_PATH.$qr_imgs);
        imagedestroy($target_qr);
        imagedestroy($source_qr);


        $h_time=$time."_1";

        $h_name ='qrcode/'.$h_time.'.jpg';
        //判断 用户头像格式 转 JPG 格式,改变头像图片大小
        if(!$this->fileSystem->exists('qrcode/'.$h_time.'.jpg')){
            $h_name=resizejpg(ROOT_PATH.$h_imgsrc,$hearimg_width,$hearimg_hight,$h_time);
        }

        //头像
        $h_imgs = $h_name;

        //背景图片
        if(!$this->fileSystem->exists($bg_img) )
        {
            $target =__DIR__. 'data/qrcode/tianxin100.jpg';//背景图片

        }
        $target =__DIR__  . $bg_img ;//背景图片 mobile下的qrcode 文件下

        $target_img = Imagecreatefromjpeg($target);
        $source = Imagecreatefromjpeg(ROOT_PATH.$qr_imgs );
        $h_source = Imagecreatefromjpeg(ROOT_PATH. $h_imgs);
        imagecopy($target_img,$source,$qr_x,$qr_y,0,0,$qr_width,$qr_height);//创建二维码图片大小
        imagecopy($target_img,$h_source,$hearimg_x,$hearimg_y,0,0,$hearimg_width,$hearimg_hight);//创建头像大小
        $fontfile =__DIR__. "data/qrcode/simsun.ttf";

        #水印文字 合成用户名
        $nickname = empty($wechat->getNickName())? $wechat->getUser()->getUsername() : $wechat->getNickName();
        #打水印
        $textcolor = imagecolorallocate($target_img, $text_red, $text_geren, $text_blue);

        imagettftext($target_img,18,0,188,129,$textcolor,$fontfile,$nickname);
        Imagejpeg($target_img, __DIR__.'qrcode/'.$time.'.jpg');

        imagedestroy($target_img);
        imagedestroy($source);
        imagedestroy($h_source);
        $s_data= $time.'.jpg';

        return $s_data ;


    }

    //创建头像
    public function createFaceImg(WeChat $weChat,$fname)
    {
        $time = substr($fname,0,-4);
        //头像获取
        $h_imgsrc= "/images/qrcode/head/".$time.".jpg";

        if( !$this->fileSystem->exists( $h_imgsrc) )
        {
            if(!$weChat->getHeadImg()){
                $h_imgsrc = "data/qrcode/headImg.jpg";
            }
            $this->fileSystem->copy($weChat->getHeadImg(),$h_imgsrc,true);
        }
        return $h_imgsrc;
    }

    //获取微信用户基本信息
    public function getUserInfo($openId)
    {
        $user = $this->app->user->get($openId);
        $userInfo = json_decode($user);
        return $userInfo;
    }

    //获取微信用户带参数二维码
    public function getQrcode($scene_id)
    {
        $qrUrl = $this->app->qrcode->forever($scene_id);
        $url = $this->app->qrcode->url($qrUrl['ticket']);
        $imgPath = "qrcode/scene/{$scene_id}.jpg";
        $this->fileSystem->copy($url,$imgPath,true);
        return $imgPath;
    }

    //获取access_token
    public function getAccessToken()
    {
        $expiresIn = $this->wechatConfig->getExpiresIn();
        $accessToken = $this->wechatConfig->getAccessToken();
        if(time() >= $expiresIn){
            $accessToken = $this->app->access_token->getToken(true);
            $this->wechatConfig->setAccessToken($accessToken);
            $this->wechatConfig->setExpiresIn(time('now') + 7200);
        }

        return $accessToken;
    }

    //创建微信菜单
    public function createMenu()
    {
        $menu = [
            [
                "name" => "玉泷集团",
                "sub_button"  => [
                    [
                        "type" => "view",
                        "name" => "公司简介",
                        "url" => ""
                    ],
                    [
                        "type" => "view",
                        "name" => "活动资讯",
                    ]
                ]
            ],
            [
                "name" => "商城首页",
                "type" => "view",
                "url" => "http://bhyulong.cn"
            ],
            [
                "name" =>"会员中心",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "联系客服",
                        "url"  => "http://www.soso.com/"
                    ],
                    [
                        "type" => "view",
                        "name" => "申请合作",
                        "url"  => "http://v.qq.com/"
                    ],
                    [
                        "type" => "view",
                        "name" => "会员中心",
                        "url" => "http://v.qq.com/"
                    ],
                ],
            ]
        ];
        $this->app->menu->create($menu);
    }

    //关键词回复
    public function keywordsRefund($keyword)
    {
        $keyword = strtolower($keyword);
        if($keyword == "wifi")
        {
            return"wifi 密码是: 8888888";
        }
        $goods = $this->em->getRepository(Goods::class)->findByKeyword($keyword);
        $items = [
            new NewsItem([
                'title' => $goods->getName(),
                "description" =>$goods->getDescription(),
                "url" =>$goods->getUrl(),
                "image"=>$goods->getTitleImg()
            ])
        ];
        $news = new News($items);
        return $news;
    }


    //通过OpenId查找会员
    public function findMemberByOpenId($openId)
    {
        return $this->em->getRepository(WeChat::class)->findOneBy(['openid'=>$openId]);
    }

    //微信对接验证
    public function validate(Request $request)
    {
        $signature = trim($request->get('signature'));
        $timestamp = trim($request->get('timestamp'));
        $nonce = trim($request->get('nonce'));
        $echostr = trim($request->get('echostr'));
        $wechat = $this->wechatConfig;
        $token = $wechat->getToken();

        $tmparr = array($token,$timestamp,$nonce);
        sort($tmparr);
        $tmpstr = implode($tmparr);
        $vlsignatu = sha1($tmpstr);
        if($vlsignatu != $signature){
            return 'error<br/>'. 'signature='.$signature. '<br/>vlsignatu='.$vlsignatu;
        }
        return $echostr;
    }

    public function listenToWechat(Request $request)
    {

        $this->app->server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'event':
                    if($message['Event'] == "subscribe")
                    {
                        return "欢迎关注本公众号";
                    }
                    return '收到事件消息';
                    break;
                case 'text':
                    if(strtolower($message['Content']) == "wifi")
                    {
                        return "wifi 密码是: yl888888";
                    }
                    $goods = $this->em->getRepository(Goods::class)->findByKeyword($message['Content']);
                    $items = [
                        new NewsItem([
                            'title' => $goods->getName(),
                            "description" =>$goods->getDescription(),
                            "url" =>$goods->getUrl(),
                            "image"=>$goods->getTitleImg()
                        ])
                    ];
                    $news = new News($items);
                    return $news;
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                default:
                    return '收到其它消息';
                    break;
            }
            return "您好！欢迎使用 EasyWeChat";
        });
        //服务记录
        $this->app->logger->extend('single', function($app, $config){
            return new Logger($this->parseChannel($config), [
                $this->prepareHandler(new RotatingFileHandler(
                    $config['path'], $config['days'], $this->level($config)
                )),
            ]);
        });
        $response = $this->app->server->serve();
        return $response;

        $response = $request->getContent();
        if(empty($response))
        {
            return;
        }
        $data = simplexml_load_string($request->getContent());
        $fromUsername = $data -> FromUserName;
        $msgType = $data -> MsgType;
        $this->returnData['toUser'] = $data -> ToUserName;
        $keyword = trim($data -> Content);

        $curWechat = $this->em->getRepository(WeChat::class)->findOneBy(['openid'=>$fromUsername]);
        $this->returnData['fromUser'] = $data -> ToUserName;

        //判断是否是事件，微信事件处理
        if($msgType == "event")
        {
            $event = $data->Event;

            //判断是否是关注事件
            if($event == "subscribe")
            {
                if(!$curWechat instanceof WeChat)
                {   //新用户将自动注册
                    $curWechat = $this->register($fromUsername);
                }

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
                        $this->returnData['content'] ="错误提示：推荐关系不合法可能情况1：自己不能成为自己的下级2：自己有下级后不能成为别人的下级";
                    }

                    $member->addPoints(1,"推荐下线成功");
                    $this->returnData['toUser'] = $parentWechat->getOpenid();
                    $this->returnData['msgType'] = 'news';
                    $this->returnData['news'] = array(
                        "articles" => array(
                            "title" =>"您有新朋友加入了，赶紧看看吧",
                            "description" =>"新朋友的消费您都将有积分哦",
                            "url" =>"http://bhyulong.cn/distribute.php",
                            "picurl" => "http://bhyulong.cn/images/bh_dichan_icon.png"
                        )
                    );
                    //向微信显示内容
                    $this->sendNews($this->returnData);
                    $shop_name = "玉泷商城";
                    $this->returnData['content'] = "恭喜您由".$parentWechat->getNickName()."推荐成为".$shop_name."的会员！点击左下角“".$shop_name."”立即购买成为".$shop_name."掌柜，裂变你的代理商，让你每天睡觉都能赚大钱！";
                }

                //向微信返回信息
                return $this->sendText($this->returnData);
            }
            //取消关注事件
            if($event == "unsubscribe"){
                $curWechat->setSubscribe(false);
            }
            //用户已经关注时才会出现的扫码事件
            if($event == "SCAN"){
                //用户已经关注过
                if($curWechat->getSubscribe())
                {
                    $this->returnData['content'] = "您已经关注过，请不要重复关注";
                }
                $this->returnData['toUser'] = $curWechat->getOpenid();
                return $this->sendText($data);
            }
        }
        elseif($msgType == "text")//文本消息处理
        {
            $keyword = trim($data -> Content);
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
        }else//默认情况处理
        {
            $this->returnData['content'] = '您已经关注过请不要重复关注!';
        }
        $this->returnData['msgType'] = 'text';
        $this->returnData['content'] = '您已经关注过请不要重复关注!';
        return $this->sendText($this->returnData);
    }

    //自动向用户发送文本消息
    public function sendText($data)
    {
        $textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><Content><![CDATA[%s]]></Content></xml>";
        $resTpl = sprintf($textTpl,$data['toUser'],$data['fromUser'],time(),$data['msgType'],$data['content']);
        return $resTpl;
    }

    //向用户发送图文链接客服消息
    public function sendNews($data)
    {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
        $this->curl->post($url,json_encode($data));
        $res = $this->curl->getResponse();
        return $res;
    }



    //对数组或对象里的中文进行url编码
    function url_encode($data)
    {
        if(is_array($data) || is_object($data)){
            foreach ($data as $k => $v){
                if(is_scalar($v)){
                    if(is_array($data)){
                        $data[$k] = urlencode($v);
                    }elseif (is_object($data)){
                        $data->$k = urlencode($v);
                    }
                }elseif(is_array($v) && is_array($data) || is_object($v) && is_array($data)){
                    $data[$k] = $this->url_encode($v);
                }elseif(is_object($data)){
                    $data->$k = $this->url_encode($v);
                }
            }
        }
        return $data;
    }

    public function zh_json_encode($data){
        $data = $this->url_encode($data);
        $data = json_encode($data);
        return urldecode($data);
    }



}