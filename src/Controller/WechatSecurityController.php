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
        $openId = $request->cookies->get('openid');
        if(!$openId)
        {
            return $wechatserver->getAuth();
        }
        else {
            $form = $this->createForm(MemberLoginForm::class);
            $form->handleRequest($request);
            $f = $form->createView();
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
}
