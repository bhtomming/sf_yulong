<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/27
 * Time: 23:32
 * Site: http://www.drupai.com
 */

namespace App\Controller;


use App\Entity\Address;
use App\Entity\Exchange;
use App\Entity\Member;
use App\Entity\User;
use App\Entity\WeChat;
use App\Entity\WechatConfig;
use App\Form\AddressForm;
use App\Form\ExchangeForm;
use App\Servers\WeChatServer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Yansongda\Pay\Pay;

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
    public function exchange(Request $request)
    {
        $form = $this->createForm(ExchangeForm::class,new Exchange());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $config = $this->getWechatPayConfig();
            $info = $data->getPayInfo();
            $info['openid'] = $this->getWechat()->getOpenid();
            $info['notify_url'] = $this->generateUrl("wx_respond",[], UrlGeneratorInterface::ABSOLUTE_URL);
            dump($config);exit;
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            $pay = Pay::wechat($config)->mp($info);

            return $this->redirectToRoute('member_center');
        }

        return $this->render("member/exchange.html.twig",[
            'form' => $form->createView()
        ]);
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
        $addreses = $this->getMember()->getAddress();
        return $this->render("member/address_list.html.twig",['addresses' => $addreses]);
    }

    /**
     * @Route("/address/add", name="address_add")
     * 添加收货地址
     */
    public function addAddress(Request $request)
    {
        $member = $this->getMember();
        $address = new Address();
        $form = $this->createForm(AddressForm::class,$address);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $address = $form->getData();
            $member->addAddress($address);
            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush();
            return $this->redirectToRoute("address_list");
        }

        return $this->render('member/address_add.html.twig',[
            'form'=>$form->createView(),
            'member' => $member
        ]);
    }

    /**
     * @Route("/reply/list", name="reply_list")
     * 会员留言列表
     */
    public function replyList()
    {
        $replies = $this->getMember()->getReplies();
        return $this->render("member/reply_list.html.twig",['replies'=>$replies]);
    }

    /**
     * @Route("/assess/list", name="assess_list")
     * 会员评论列表
     */
    public function assessList()
    {
        $assesses = $this->getMember()->getAssess();
        return $this->render("member/assess_list.html.twig",['assesses'=>$assesses]);
    }

    /**
     * @Route("/rank/list", name="rank_list")
     * 会员积分排行列表
     */
    public function rankList()
    {
        return $this->render("member/ranking_list.html.twig");
    }

    public function getWechat(): ? WeChat
    {
        return $this->getUser();
    }

    public function getWUser(): ?User
    {
        return $this->getWechat()->getUser();
    }

    public function getMember(): ?Member
    {
        return $this->getWUser()->getMember();
    }

    public function getWechatPayConfig()
    {
        $em = $this->getDoctrine()->getManager();
        $wechatConfig = $em->getRepository(WechatConfig::class)->find(1);
        return $wechatConfig->getPayConfig();
    }



}