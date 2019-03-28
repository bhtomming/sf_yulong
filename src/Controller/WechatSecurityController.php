<?php

namespace App\Controller;

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
        $wechat  = $wechatserver->getWechatNoId();
        //dump($openId);exit;
        if(!$wechat)
        {
            return $wechatserver->getAuth();
        }
dump($wechat);exit;
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
}
