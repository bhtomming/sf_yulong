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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


class WeChatServer
{
    private $em;

    private $wechatConfig;

    private $fileSystem;

    private $curl;

    private $wechat;

    private $app;

    private $baseUrl;

    private $container;

    private $weManager;




    public function __construct(EntityManagerInterface $em,ContainerInterface $container = null,WechatManager $weManager = null)
    {
        $this->em = $em;
        $this->wechatConfig = $this->getWeChatConfig();
        $this->curl = new Curl();
        $this->fileSystem = new Filesystem();
        $this->app = $this->getApp();
        $this->baseUrl = "http://weixin.drupai.com";
        $this->container = $container;
        $this->weManager = $weManager;
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

    //更新微信用户信息
    public function update(WeChat $weChat)
    {
        $this->em->persist($weChat);
        $this->em->flush();
    }

    //注册会员信息
    public function register($openId): ? WeChat
    {
        $user = new User();
        $newMember = new Member();
        $weChat = new WeChat();
        $weChat->setOpenid($openId);
        $userInfo = $this->getUserInfo($openId);
        $date = new \DateTime();
        $weChat
            ->setNickName($userInfo['nickname'])
            ->setHeadImg($userInfo['headimgurl'])
            ->setCity($userInfo['city'])
            ->setSex($userInfo['sex'])
            ->setProvicne($userInfo['province'])
            ->setCountry($userInfo['country'])
            ->setSubscribe($userInfo['subscribe'] == 1)
            ->setSubscribeTime($date->setTimestamp($userInfo['subscribe_time']))
        ;
        $username = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 4, 8), 1))), 0, 5);
        $password = substr(md5(time()), 0, 8);
        $userReply = "您的账号：".$username."密码：".$password;

        $weChat->setPassword($password)
        ->setRoles(['ROLE_USER']);
        $user->setName($username)
            ->setPassword($password)
            ->setMember($newMember)
            ->setWechat($weChat)
            ->setRoles(['ROLE_USER'])
        ;
        $this->em->persist($user);
        $this->em->flush();
        $text = new Text($userReply);
        $this->app->server->push(function($message) use ($text){
            return $text;
        });
        $this->app->server->serve()->send();

        return $weChat;
    }

    //向微信获取网页授权
    public function getAuth()
    {
        $response = $this->app->oauth->scopes(['snsapi_base'])->redirect($this->baseUrl."/login_notify");
        return $response;
    }

    //获取微信返回的用户信息
    public function getAuthUser()
    {
        return $this->app->oauth->user();
    }

    //创建推荐二维码
    public function createQrcode(WeChat $wechat)
    {
        $wechat = $this->weManager->loadUserByName($wechat->getOpenid());
        $member = $wechat->getUser()->getMember();
        if(null != $refererImg = $member->getRefererImg())
        {
            return $refererImg;
        }
        $qr_width = 255;  // 是二维码图片宽度
        $qr_height = 255; // 二维码图片高度
        $qr_x = 152;
        $qr_y = 380;
        $hearimg_width = 80;
        $hearimg_hight = 80;
        $hearimg_x = 80;
        $hearimg_y = 50;
        $bg_img = "images/qrcode/1540239067195492735.jpg"; //合成图片的背景
        $text_red = 255;
        $text_geren = 0;
        $text_blue = 0;

        //===================================================================//

        //用户头像文件名
        $fname = time().'jpg';
        //图片的基本路径
        $basePath = $this->get('kernel')->getProjectDir()."/public";

        //创建用户头像文件并返回路径
        $h_imgsrc = $this->createFaceImg($wechat,$fname);

        $time = substr($fname,0,-4);

        //向微信获取用户二维码并保存到文件返回路径
        $qr_src = $this->getQrcode($wechat->getId());

        //要改变二维码的大小，需要重新命名
        $qr_imgs= $basePath."/images/qrcode/scene/".$time."_".$wechat->getId().".jpg";
        //加载图片信息
        $target_qr =  imagecreatetruecolor($qr_width,$qr_height);
        $source_qr = imagecreatefromjpeg($qr_src);
        //调整二维码大小
        Imagecopyresized($target_qr, $source_qr, 0, 0, 23, 23, $qr_width, $qr_height, 383, 383);
        imagejpeg($target_qr,$qr_imgs);
        imagedestroy($target_qr);
        imagedestroy($source_qr);


        $h_time= $basePath."/images/user_header/".$time."_1.jpg";


        //判断 用户头像格式 转 JPG 格式,改变头像图片大小
        if($this->fileSystem->exists($h_imgsrc)){
            $h_name=$this->resizejpg($h_imgsrc,$hearimg_width,$hearimg_hight,$h_time);
        }

        //头像
        $h_imgs = $h_name;

        //背景图片
        if(!$this->fileSystem->exists($bg_img) )
        {
            $target = $basePath.'/images/qrcode/tianxin100.jpg';//背景图片

        }
        $target = $bg_img ;//背景图片 mobile下的qrcode 文件下

        $target_img = Imagecreatefromjpeg($target);
        $source = Imagecreatefromjpeg($qr_imgs );
        $h_source = Imagecreatefromjpeg($h_imgs);
        imagecopy($target_img,$source,$qr_x,$qr_y,0,0,$qr_width,$qr_height);//创建二维码图片大小
        imagecopy($target_img,$h_source,$hearimg_x,$hearimg_y,0,0,$hearimg_width,$hearimg_hight);//创建头像大小
        $fontfile = $basePath."/images/qrcode/simsun.ttf";

        #水印文字 合成用户名
        $nickname = empty($wechat->getNickName())? $wechat->getUser()->getUsername() : $wechat->getNickName();
        #打水印
        $textcolor = imagecolorallocate($target_img, $text_red, $text_geren, $text_blue);

        $recomImage = '/images/user_header/'.$time.'.jpg';
        imagettftext($target_img,18,0,188,129,$textcolor,$fontfile,$nickname);
        Imagejpeg($target_img, $basePath.$recomImage);

        imagedestroy($target_img);
        imagedestroy($source);
        imagedestroy($h_source);


        $member->setRefererImg($recomImage);
        $this->update($wechat);

        return $recomImage ;


    }

    //创建头像
    public function createFaceImg(WeChat $weChat,$fname)
    {
        $time = substr($fname,0,-4);
        //头像获取
        $h_imgsrc= "images/user_header/".$time.".jpg";

        if( !$this->fileSystem->exists( $h_imgsrc) )
        {
            if(!$weChat->getHeadImg()){
                $h_imgsrc = "images/qrcode/headImg.jpg";
            }
            $this->fileSystem->copy($weChat->getHeadImg(),$h_imgsrc,true);
        }
        return $h_imgsrc;
    }

    //获取微信用户基本信息
    public function getUserInfo($openId)
    {
        $user = $this->app->user->get($openId);
        //$userInfo = json_decode($user);
        return $user;
    }

    //获取微信用户带参数二维码
    public function getQrcode($scene_id)
    {
        $qrUrl = $this->app->qrcode->forever($scene_id);
        $url = $this->app->qrcode->url($qrUrl['ticket']);
        $imgPath = "images/qrcode/scene/{$scene_id}.jpg";
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
        //用户查询WIFI时回复
        if($keyword == "wifi")
        {
            return"wifi 密码是: 8888888";
        }
        $goods = $this->em->getRepository(Goods::class)->findByKeyword($keyword);
        if(!$goods instanceof Goods)
        {
            return "没有找到您要的内容";
        }
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
        //服务器收到消息记录
        $this->app->logger->extend('single', function($app, $config){
            return new Logger($this->parseChannel($config), [
                $this->prepareHandler(new RotatingFileHandler(
                    $config['path'], $config['days'], $this->level($config)
                )),
            ]);
        });

        $this->app->server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'event':
                    //关注事件处理
                    if($message['Event'] == "subscribe")
                    {
                        return $this->subscribe($message);
                    }
                    //取消关注事件
                    if($message['Event'] == "unsubscribe"){
                        $this->unsubscribe($message);
                    }
                    return "您已经关注过，请不要重复关注";
                    break;
                case 'text':
                    return $this->keywordsRefund($message['Content']);
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
        });

        $response = $this->app->server->serve();
        return $response;

    }

    //关注处理
    public function subscribe($msg)
    {
        $curOpenId = $msg['FromUserName'];
        $curWechat = $this->getWechat($curOpenId);
        //获取二维码中的推荐ID
        $sceneId = substr($msg['EventKey'],7);

        //判断是否是新用户
        if(!$curWechat instanceof WeChat)
        {   //新用户将自动注册
            $curWechat = $this->register($curOpenId);
            //$member = $curWechat->getUser()->getMember();

            //二维码中存在推荐人ID
            if(!empty($sceneId))
            {
                //有推荐人的情况下
                $parentUser = $this->em->getRepository(User::class)->find($sceneId);
                if(!$parentUser instanceof User)
                {
                    return "推荐人信息不正确";
                }
                if($sceneId == $curWechat->getUser()->getId() || $curWechat->getUser()->getMember()->getMembers()->count() !=0)
                {
                    return "错误提示：推荐关系不合法可能情况1：自己不能成为自己的下级2：自己有下级后不能成为别人的下级";
                }
                $parentMember = $parentUser->getMember();
                //给推荐人加1分
                $parentMember->addPoints(1,"推荐1人关注公众号");
                //设置当前用户的推荐人
                $parentMember->addMember($curWechat->getUser()->getMember());
                $items = [new NewsItem([
                    "title" =>"您有新朋友加入了，赶紧看看吧",
                    "description" =>"新朋友的消费您都将有积分哦",
                    "url" =>"http://bhyulong.cn/distribute.php",
                    "picurl" => "http://bhyulong.cn/images/bh_dichan_icon.png"
                ])];
                $parentWechat = $parentMember->getUser()->getWechat();
                $news = new News($items);
                //向用户的上级发送消息
                $this->app->customer_service->message($news)->to($parentWechat->getOpenid())->send;

                $shop_name = "玉泷商城";

                return "恭喜您由".$parentWechat->getNickName()."推荐成为".$shop_name."的会员！点击左下角“".$shop_name."”立即购买成为".$shop_name."掌柜，裂变你的代理商，让你每天睡觉都能赚大钱！";
            }
        }

        //老用户把关注设置一下。
        $curWechat->setSubscribe(true);

        //向微信返回信息
        return "欢迎关注玉泷商城";

    }

    //取消关注处理
    public function unsubscribe($msg)
    {
        $curWechat = $this->getWechat($msg['FromUserName']);
        $curWechat->setSubscribe(false);
    }

    public function getWechat($openId): ? WeChat
    {
        $wechat = $this->em->getRepository(WeChat::class)->findOneBy(['openid'=>$openId]);
        /*if(!$wechat instanceof WeChat)
        {
            $wechat = $this->register($openId);
        }*/
        return $wechat;
    }

    //微信登录
    public function login($openid)
    {
        $this->wechat = $this->getWechat($openid);
        return $this->wechat;
    }

    //是否已经登录
    public function isLogin()
    {
        return $this->wechat instanceof WeChat;
    }

    public function getWechatNoId()
    {
        return $this->wechat;
    }

    public function get($server)
    {
        return $this->container->get($server);
    }

    public function resizejpg($imgsrc, $imgwidth, $imgheight, $path)
    {
        //$imgsrc jpg格式图像路径 $imgdst jpg格式图像保存文件名 $imgwidth要改变的宽度 $imgheight要改变的高度
        //取得图片的宽度,高度值

        $arr = getimagesize($imgsrc);
        //header("Content-type: image/jpg");
        $imgWidth = $imgwidth;
        $imgHeight = $imgheight;
        $imgsrc = imagecreatefromjpeg($imgsrc);//识别图像类型 创建类型图像
        $image = imagecreatetruecolor($imgWidth, $imgHeight);//返回一个图像标识符，代表了一幅大小为 x_size 和 y_size 的黑色图像。
        imagecopyresampled($image, $imgsrc, 0, 0, 0, 0, $imgWidth, $imgHeight, $arr[0], $arr[1]);
        /*
         bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )

                $dst_image：新建的图片

                $src_image：需要载入的图片

                $dst_x：设定需要载入的图片在新图中的x坐标

                $dst_y：设定需要载入的图片在新图中的y坐标

                $src_x：设定载入图片要载入的区域x坐标

                $src_y：设定载入图片要载入的区域y坐标

                $dst_w：设定载入的原图的宽度（在此设置缩放）

                $dst_h：设定载入的原图的高度（在此设置缩放）

                $src_w：原图要载入的宽度

                $src_h：原图要载入的高度
    */
        Imagejpeg($image, $path);
        return $path;
    }



}