<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/5
 * Time: 22:48
 * Site: http://www.drupai.com
 */

namespace App\Security\Firewall;


use App\Event\AuthorizeEvent;
use App\Event\Events;
use App\Security\Token\WechatUserToken;
use EasyWeChat\OfficialAccount\Application;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Http\HttpUtils;

use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
class WechatListener implements ListenerInterface
{
    const REDIRECT_URL_KEY = '_wechat.redirect_url';

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     *
     */
    protected $authenticationManager;

    /**
     * @var array
     */
    protected $options = array(
        'authorize_path' => '/member/authorize',
        'default_redirect' => '/member',
    );

    /**
     * @var HttpUtils
     */
    protected $httpUtils;

    /**
     * @var Application
     */
    private $sdk;

    /**
     * @var EventDispatcherInterface
     */
    private $event_dispatcher;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        HttpUtils $httpUtils,
        Application $sdk,
        EventDispatcherInterface $event_dispatcher,
        array $options
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->httpUtils = $httpUtils;
        $this->sdk = $sdk;
        $this->event_dispatcher = $event_dispatcher;
        $this->options = array_merge($this->options,$options);
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $session = $request->getSession();

        //$oauth = $this->sdk->oauth;

        //授权页面
        /*if($this->httpUtils->checkRequestPath($request,$this->options['authorize_path']))
        {
            $user = $oauth->user()->getOriginal();

            $wechatAuthorizeEvent = new AuthorizeEvent($user);


            $this->event_dispatcher->dispatch(Events::AUTHORIZE,$wechatAuthorizeEvent);

            $token = new WechatUserToken($user['openid'],['ROLE_USER','ROLE_WECHAT_USER']);

            $this->tokenStorage->setToken($token);

            $this->event_dispatcher->dispatch(AuthenticationEvents::AUTHENTICATION_SUCCESS,new AuthenticationEvent($token));


            $redirectUrl = $session->get(self::REDIRECT_URL_KEY) ?: $request->getUriForPath($this->options['default_redirect']);

            $session->remove(self::REDIRECT_URL_KEY);

            $event->setResponse(new RedirectResponse($redirectUrl));

            return;


        }*/

        /*$token = $this->tokenStorage->getToken();

        if($token !== null){
            $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($token);

            return;
        }*/

        // 未授权, 重定向到微信授权页面
        $session->set(self::REDIRECT_URL_KEY, $request->getUri());
        /*$target_url = $request->getUriForPath($this->options['authorize_path']);
        $response = $oauth->scopes(['snsapi_userinfo'])->redirect($target_url);
        $event->setResponse($response);*/
    }
}