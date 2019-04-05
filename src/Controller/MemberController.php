<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/27
 * Time: 23:32
 * Site: http://www.drupai.com
 */

namespace App\Controller;


use App\Entity\WeChat;
use App\Servers\WeChatServer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;

/**
 * Class MemberController
 * @package App\Controller
 * @Route("/member")
 * @IsGranted("ROLE_USER")
 */

class MemberController extends AbstractController
{


    /**
     * @Route("/", name="member_center")
     * 用户中心主页
     */
    public function index()
    {
        return $this->render("member/index.html.twig");
    }

    /**
     * @Route("/salelink", name="member_sale_link")
     * 推荐页面
     */
    public function saleLink(WeChatServer $wechatServer)
    {
        $wechat = $this->getUser();
        $image = $wechatServer->createQrcode($wechat);

        return $this->render("member/recommend.html.twig",['image'=>$image]);
    }

    /**
     * @Route("/exchange", name="member_exchange")
     * 充值页面
     */
    public function exchange()
    {
        return $this->render("member/exchange.html.twig");
    }


    /**
     * @Route("/cash", name="member_cash")
     * 提现页面
     */
    public function cash()
    {
        return $this->render("member/cash.html.twig");
    }

    /**
     * @Route("/sale/center", name="sale_center")
     * 销售中心
     */
    public function saleCenter()
    {
        return $this->render("member/salecenter.html.twig");
    }

    /**
     * @Route("/edit", name="member_edit")
     * 编辑用户信息
     */
    public function edit()
    {
        return $this->render("member/edit.html.twig");
    }

    /**
     * @Route("/order/list", name="order_list")
     * 订单列表
     */
    public function orderList()
    {
        return $this->render("member/order_list.html.twig");
    }

    /**
     * @Route("/address/list", name="address_list")
     * 收货地址
     */
    public function addressList()
    {
        return $this->render("member/address_list.html.twig");
    }

    /**
     * @Route("/reply/list", name="reply_list")
     * 会员留言列表
     */
    public function replyList()
    {
        return $this->render("member/address_list.html.twig");
    }

    /**
     * @Route("/assess/list", name="assess_list")
     * 会员评论列表
     */
    public function assessList()
    {
        return $this->render("member/assess_list.html.twig");
    }

    public function getWechat()
    {
        return $this->getUser();
    }

    public function getWUser()
    {
        return $this->getWechat()->getUser();
    }

    public function getMember()
    {
        return $this->getUser()->getMember();
    }

}