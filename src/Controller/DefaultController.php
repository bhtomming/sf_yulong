<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/27
 * Time: 22:58
 * Site: http://www.drupai.com
 */

namespace App\Controller;


use App\Entity\Cart;
use App\Entity\Category;
use App\Entity\Exchange;
use App\Entity\Goods;
use App\Entity\GoodsSnapshot;
use App\Entity\PayLog;
use App\Entity\PointsConfig;
use App\Entity\PointsLog;
use App\Entity\Trade;
use App\Entity\User;
use App\Entity\WeChat;
use App\Entity\WechatConfig;
use App\Servers\MemberManager;
use App\Servers\WeChatServer;
use EasyWeChat\Kernel\Messages\NewsItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Wythe\Logistics\Logistics;
use Yansongda\Pay\Pay;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class DefaultController extends AbstractController
{
    /**
     * @Route("/",name="home_page")
     * 网站首页
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $categoryHotel = $em->getRepository(Category::class)->find(1);
        if($categoryHotel instanceof Category)
        {
            $categoryHotel = $categoryHotel->getGoodsBySort();
        }

        return $this->render('default/index.html.twig',['hts' => $categoryHotel]);
    }


    /**
     * @Route("/hot", name="hot_sale")
     * 热销商品页面
     */
    public function hotSales()
    {
        $em = $this->getDoctrine()->getManager();
        $goodses = $em->getRepository(Goods::class)->findByHot();

        return $this->render('default/hot.html.twig',['goodses' => $goodses]);
    }


    /**
     * @Route("/list/{id}", name="goods_list")
     *
     * 按分类显示商品
     */
    public function goodsList(Category $category)
    {
        return $this->render("default/category.html.twig",['category' => $category]);
    }


    /**
     * @Route("/goods/{id}", name="goods_show")
     *
     * 商品详情页面
     */
    public function goodsShow(Goods $goods)
    {
        return $this->render("default/show.html.twig",['goods' => $goods]);
    }


    /**
     * @Route("/cart/add/{goods_id}/{num}", name="add_cart")
     *
     * @IsGranted("ROLE_USER")
     */
    public function addCart(Goods $goods, $num)
    {
        $member = $this->getMemberIfLogin();

        $cart = new Cart();
        if(!$goods->getSaling()){
            return $this->createNotFoundException(['该商品已经下架']);
        }
        $cart->setGoods($goods);
        if($num > $goods->getStock()){
            return $this->createNotFoundException(['该商品库存不足']);
        }
        $cart->setNum($num);
        $member->addCart($cart);
        return $this->render("default/cart.html.twig",['cart' => $cart]);
    }

    /**
     * @Route("/cart/del/{id}/{num}", name="remove_cart")
     *
     * @IsGranted("ROLE_USER")
     */
    public function removeCart(Cart $cart)
    {
        $member = $this->getMemberIfLogin();
        $member->removeCart($cart);
        return $this->redirectToRoute("list_cart");
    }

    /**
     * @Route("/cart/list", name="list_cart")
     * 购物车列表
     * @IsGranted("ROLE_USER")
     *
     */
    public function listCart()
    {
        $member = $this->getMemberIfLogin();
        $carts = $member->getCarts();

        return $this->render("default/cart_list.html.twig",['carts'=>$carts]);
    }


    /**
     * @Route("/login_notify/", name="login_notify")
     * 处理登录返回信息
     */
    public function loginNotify(WeChatServer $chatServer)
    {
        //$user = $chatServer->getAuthUser();
        //$openid = $user->getId();
        $openid = "oU2f4s568lSE0XvNTE4mXJq-ll_I";
        if($openid == null){
            //未关注用户要求先关注并注册
            $this->addFlash('error','请先关注再登录');
            return $this->render("exceptions/access_exception.html.twig");
        }

        $wechat = $chatServer->login($openid);

        if(!$wechat instanceof WeChat)
        {
            //未注册用户自动帮注册
            $chatServer->register($openid);
        }

        return $this->redirectToRoute('app_login',['openid'=>$openid]);
    }

    /**
     * @Route("/order/add", name="add_order")
     * 下订单,生成订单
     */
    public function addOrder()
    {
        $member = $this->getMemberIfLogin();
        $carts = $member->getCarts();
        //创建订单
        $trade = new Trade();

        $em = $this->getDoctrine()->getManager();
        $pointsConfig = $em->getRepository(PointsConfig::class)->find(1);
        $pointRest = $pointsConfig->getGivePoint() / 100;
        $points = 0;
        $amount = 0;
        //添加订单商品，形成商品快照
        foreach ($carts as $cart){
            $goodsSnapshot = new GoodsSnapshot();
            $goods = $cart->getGoods();
            $num = $cart->getNum();
            $price = $goods->getPrice();
            $amount += $price * $num;
            $points += $price * $pointRest * $num;
            $goodsSnapshot->setGoodsId($goods->getId());
            $goodsSnapshot->setGoodsName($goods->getName());
            $goodsSnapshot->getGoodsImg($goods->getGoodsImg);
            $goodsSnapshot->setGoodsNum($num);
            $goodsSnapshot->setGoodsPrice($price);
            $goodsSnapshot->setGoodsLink("goods/show/".$goods->getId());
            $trade->addGoodsSnapshot($goodsSnapshot);
        }
        //订单送的积分
        $trade->setGivePoints($points);
        //订单总金额
        $trade->setTotalAmount($amount);
        $member->addTrade($trade);

        return $this->render('default/show.html.twig',['trade'=>$trade]);
    }

    /**
     * @Route("/paying/{id}", name="paying")
     * 支付页面
     */
    public function paying(Trade $trade)
    {
        $config = $this->getWechatPayConfig();
        $pay = Pay::wechat($config)->mp($trade->getWePayInfo());
    }

    /**
     * @Route("/respond", name="wx_respond")
     * 微信支付返回页面
     */
    public function respond()
    {
        $pay = Pay::wechat($this->getWechatPayConfig());

        try{
            $data = $pay->verify(); // 是的，验签就这么简单！
            if($data['return_code'] != 'SUCCESS'){
                return $this->createNotFoundException(['支付失败']);
            }
            $tradeNo = $data['out_trade_no'];
            $em = $this->getDoctrine()->getManager();
            $payType = $data['attach'];

            switch ($payType){
                case "CZ":
                    //充值处理
                    $exchange = $em->getRepository(Exchange::class)->findOneBy(['changeNo' => $tradeNo]);
                    if(!$exchange instanceof Exchange)
                    {
                        echo 'invalidate';
                        return false;
                    }
                    //处理充值金额
                    $member = $exchange->getMember();
                    $member->addAmount($exchange->getAmount());
                    $exchange->setStatus("已到账");
                    $amountLog = new PointsLog();
                    $amountLog->setAmount($exchange->getAmount())
                        ->setChangeReason("微信充值")
                        ->setMember($member);
                    break;

                default:
                    //普通订单处理
                    $trade = $em->getRepository(Trade::class)->findOneBy(['tradeNo' => $tradeNo]);
                    if(!$trade instanceof Trade){
                        echo 'invalidate';
                        return false;
                    }
                    $points = $trade->getPayPoint();

                    $trade->setStatus(Trade::PAIED);
                    $member = $trade->getMember();

                    $payLog = new PayLog();
                    $payLog->setPoints($points)
                        ->setStatus("微信支付")
                        ->setPayNo($data['transaction_id'])
                        ->setPayTime(new \DateTime('now'))
                        ->setTotalFee($data['cash_fee'])
                        ->setMember($member);
                    $trade->setPayLog($payLog);
                    $memberManager = new MemberManager($em);
                    //分配订单积分
                    $memberManager->distribute($member,$points);
                    break;
            }
            $em->persist($member);
            $em->flush();

            Log::debug('Wechat notify', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }

        return $pay->success()->send();

    }

    /**
     * @Route("/logistics", name="logistics")
     * 快递查询页面
     */
    public function logistics()
    {
        $logistics = new Logistics();
        $info = $logistics->query('73111467572929','ickd');
        /*
         * $info["ickd"] 根数组
         * $info["ickd"]["status"]快递状态
         * $info["ickd"]["result"]返回查询的结果
         * $info["ickd"]["result"]["data"]结果的具体数据，每条数据是一个二维数组，每个数组包含time,description两个元数据
         * $info["ickd"]["result"]["logistics_company"] 快递公司名称
         * $info["ickd"]["result"]["logistics_bill_no"] 快递单号
         */
        dump($info);
        exit;
    }



    /**
     * @Route("/wechat", name="wx_api")
     * 微信服务监听页面
     */
    public function wechatInterface(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $wechatServer = new WeChatServer($em);
        /*$signature = trim($request->get('signature'));
        if(!empty($signature))
        {
            return new Response($wechatServer->validate($request));
        }*/


        return $wechatServer->listenToWechat($request);
    }



    public function getWechatPayConfig()
    {
        $em = $this->getDoctrine()->getManager();
        $wechatConfig = $em->getRepository(WechatConfig::class)->find(1);
        return $wechatConfig->getPayConfig();
    }

    public function getMemberIfLogin()
    {
        $user = $this->getUser();
        if(!($user instanceof User)){
            //用户未登录重定向
            return $this->redirectToRoute("home_page");
        }
        $member = $user->getMember();
        return $member;
    }

}