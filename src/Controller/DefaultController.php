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
use Symfony\Component\DependencyInjection\Tests\Compiler\J;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/cart/add/", name="add_cart")
     *
     * @IsGranted("ROLE_USER")
     */
    public function addCart(Request $request)
    {

        $data['id'] = $id = $request->request->get('id');
        $num = $request->request->get('num');
        $em = $this->getDoctrine()->getManager();

        $goods = $em->getRepository(Goods::class)->find($id);
        $data['status'] = 200;

        if(!$goods instanceof Goods){
            $data['msg'] = '没有找到你要的商品';
        }

        $member = $this->getMemberIfLogin();


        $cart = new Cart();
        if(!$goods->getSaling()){
            $data['msg'] = '该商品已经下架';
        }
        $cart->setGoods($goods);
        if($num > $goods->getStock()){
            $data['msg'] ='该商品库存不足';
        }
        $data['price'] =$goods->getPrice() * $num;
        $data['disPrice'] = $goods->getDiscountPrice() * $num;

        $cart->setNum($num);
        $member->addCart($cart);
        $em->persist($member);
        $em->flush();

        return new JsonResponse($data);
        //return $this->render("default/cart.html.twig",['cart' => $cart]);
    }

    /**
     * @Route("/cart/del", name="remove_cart")
     *
     * @IsGranted("ROLE_USER")
     */
    public function removeCart(Request $request)
    {
        $carts = $request->request->get('carts');

        $data['status'] = 200;
        if(empty($carts)){
            $data['msg'] = '请选择一个清单';
        }
        $em = $this->getDoctrine()->getManager();
        $member = $this->getMemberIfLogin();
        foreach ($carts as $cartId){
            $cart = $em->getRepository(Cart::class)->find($cartId);
            if(!$cart instanceof Cart){
                $data['msg'] = '没有你的购物清单';
            }else{
                $member->removeCart($cart);
                $data['msg'] = '删除成功';
            }
        }

        $em->persist($member);
        $em->flush();

        return new JsonResponse($data);
        //return $this->redirectToRoute("list_cart");
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
     * @IsGranted("ROLE_USER")
     * 下订单,生成订单
     */
    public function addOrder(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cartsId = $request->request->get('carts');
        $data = [
            'status' => 201,
            'msg' => '',
        ];
        $member = $this->getMemberIfLogin();
        $carts = $member->getCarts();
        if(null != $cartsId){
            foreach ($cartsId as $id){
                $carts[] = $em->getRepository(Cart::class)->find($id);
            }
        }
        //创建订单
        $trade = new Trade();

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
            $goodsSnapshot->setGoodsImg($goods->getTitleImg());
            $goodsSnapshot->setGoodsNum($num);
            $goodsSnapshot->setGoodsPrice($price);
            $goodsSnapshot->setTradeNo($trade->getTradeNo());
            $goodsSnapshot->setGoodsLink($this->generateUrl('goods_show',['id'=>$goods->getId()]));
            $trade->addGoodsSnapshot($goodsSnapshot);
            //清空购物车
            $em->remove($cart);
            $em->flush();
        }
        $payLog = new PayLog();
        $payLog->setPoints($points);
        $payLog->setTotalFee($amount * 100);
        $payLog->setStatus("未付款");
        $payLog->setTradeNo($trade->getTradeNo());
        $member->addPayLog($payLog);
        $trade->setPayLog($payLog);
        //订单送的积分
        $trade->setGivePoints($points);
        //订单总金额
        $trade->setTotalAmount($amount);
        $em->persist($trade);
        //$em->flush();
        $member->addTrades($trade);
        $em->persist($member);
        $em->flush();
        $data['status'] = 200;

        return new JsonResponse($data);
        //return $this->render('default/show.html.twig',['trade'=>$trade]);
    }

    /**
     * @Route("/order/show", name="show_order")
     * @IsGranted("ROLE_USER")
     * 显示订单
     */
    public function showOrder(Request $request)
    {
        $member = $this->getMemberIfLogin();
        $id = $request->query->get('id');
        if(null != $id){
            $em = $this->getDoctrine()->getManager();
            $trade = $em->getRepository(Trade::class)->find($id);
        }else{
            $trade = $member->getTrades()->last();
        }

        return $this->render('trade/show.html.twig',['trade'=>$trade]);
    }

    /**
     * @Route("/order/del", name="del_order")
     * @IsGranted("ROLE_USER")
     * 删除订单
     */
    public function delOrder(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $no = $request->request->get('no');
        $data = [
            'status' => 201,
            'msg' => "订单号无效，请查证后再删除",
        ];
        if(null == $no){
            $data['msg'] = "请添加要删除的订单号";
        }
        $order = $em->getRepository(Trade::class)->findOneBy(['tradeNo'=>$no]);
        if($order instanceof Trade){
            $member = $this->getMemberIfLogin();
            $member->removeTrades($order);
            $em->remove($order);
            $em->flush();
            $data['msg'] = "成功删除订单";
            $data['status'] = 200;
        }

        return new JsonResponse($data);

    }

    /**
     * @Route("/paying/{id}", name="paying")
     * @IsGranted("ROLE_USER")
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
                        ->setStatus("已支付")
                        ->setPayType("微信支付")
                        ->setPayNo($data['transaction_id'])
                        ->setPayTime(new \DateTime('now'))
                        ->setTotalFee($data['cash_fee'])
                        ;
                    $member->addPayLog($payLog);
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
        if(!($user instanceof WeChat)){
            //用户未登录重定向
            return $this->redirectToRoute("home_page");
        }
        $member = $user->getUser()->getMember();
        return $member;
    }

}