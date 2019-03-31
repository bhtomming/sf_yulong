<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/27
 * Time: 23:32
 * Site: http://www.drupai.com
 */

namespace App\Controller;


use App\Servers\WeChatServer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
     */
    public function index()
    {
        return $this->render("member/index.html.twig");
    }

    /**
     * @Route("/salelink", name="member_sale_link")
     */
    public function saleLink(WeChatServer $wechatServer)
    {
        $wechat = $this->getUser();
        $image = $wechatServer->createQrcode($wechat);

        return $this->render("member/recommend.html.twig",['image'=>$image]);
    }

    /**
     * @Route("/exchange", name="member_exchange")
     */
    public function exchange()
    {

    }


    /**
     * @Route("/cash", name="member_cash")
     */
    public function cash()
    {

    }

}