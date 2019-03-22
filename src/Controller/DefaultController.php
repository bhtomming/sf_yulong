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
use App\Entity\Goods;
use App\Entity\GoodsSnapshot;
use App\Entity\PayLog;
use App\Entity\PointsConfig;
use App\Entity\Trade;
use App\Entity\User;
use App\Entity\WechatConfig;
use App\Servers\MemberManager;
use App\Servers\WeChatServer;
use EasyWeChat\Kernel\Messages\NewsItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

        $hts = $categoryHotel->getGoodsBySort();

        return $this->render('default/index.html.twig',['hts' => $hts]);
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
     * @ParamConverter("category", options={"mapping"={"id"="id"}})
     * 按分类显示商品
     */
    public function goodsList(Category $category)
    {
        return $this->render("default/category.html.twig",['category' => $category]);
    }


    /**
     * @Route("/goods/{id}", name="goods_show")
     * @ParamConverter("goods", options={"mapping"={"id"="id"}})
     * 商品详情页面
     */
    public function goodsShow(Goods $goods)
    {
        return $this->render("default/show.html.twig",['goods' => $goods]);
    }


    /**
     * @Route("/cart/add/{goods_id}/{num}", name="add_cart")
     * @ParamConverter("goods", options={"mapping"={"id"="goods_id"}})
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
     * @ParamConverter("cart", options={"mapping"={"id"="id"}})
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
     * @Route("/recommend/", name="recommend")
     * 推荐页面
     */
    public function recommend(Request $request,WeChatServer $wechatServer)
    {
        $wechatServer->get_oauth2_code($request);
        $user = $this->getUser();
        if(!$user instanceof User){
            $this->redirectToRoute("login");
        }
        //$image = $wechatServer->createQrcode($user->getWeChat());
        $image = "adfd";
        return $this->render("default/recommend.html.twig",['image'=>$image]);
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
        $pointRest = $pointsConfig->getPayPoint() / 100;
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
            $trade = $em->getRepository(Trade::class)->findOneBy(['tradeNo' => $tradeNo]);
            if(!$trade instanceof Trade){
                return;
            }
            $points = $trade->getGivePoints();
            $payLog = new PayLog();
            $trade->setStatus(Trade::PAIED);
            $member = $trade->getMember();


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

            Log::debug('Wechat notify', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }

        return $pay->success()->send();

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

    /**
     * @Route("/login", name="login")
     * 微信支付返回页面
     */
    public function login()
    {
        return $this->render("default/login.html.twig");
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