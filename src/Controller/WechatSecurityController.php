<?php

namespace App\Controller;

use App\Entity\WeChat;
use App\Events\WechatEventSubscriber;
use App\Form\MemberLoginForm;
use App\Servers\WeChatServer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class WechatSecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * 普通用户登录页面
     */
    public function login(AuthenticationUtils $authenticationUtils,WeChatServer $wechatserver,Request $request): Response
    {
        $openid = $request->get('openid');
        if(null == $openid)
        {
            if($request->cookies->get("request") != null)
            {
                exit;
            }
            $request->cookies->set("request",1);
            //测试使用
            return $this->redirectToRoute("login_notify");
            //return $wechatserver->getAuth();
        }
        $wechat  = $wechatserver->getWechat($openid);

        if(!$wechat instanceof WeChat)
        {
            throw $this->createAccessDeniedException("请先关注再进来");
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(): void
    {
        // Left empty intentionally because this will be handled by Symfony.
    }


    /**
     * @Route("/member/authorize", name="member_login")
     * 普通用户登录页面
     */
    public function authorize(): void
    {

    }
}
