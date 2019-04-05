<?php

namespace App\Security;

use App\Entity\WeChat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class WechatAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    public function supports(Request $request)
    {
        //return 'app_login' === $request->attributes->get('_route')&& $request->isMethod('POST');

        if('app_login' === $request->attributes->get('_route')&& $request->get('openid'))
        {
            return true;
        }

        return 'app_login' === $request->attributes->get('_route')&& $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $openid = $request->get('openid');

        if($openid == null)
        {
            $openid = $request->request->get('openid');
        }
        $credentials = [
            'openid' => $openid,
            'password' => $request->request->get('password'),
            'csrf_token' => $request->query->get('_csrf_token'),
            //'csrf_token' => $request->request->get('_csrf_token'),
           // 'wechat' => $request->request->get('openid') != null,
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['openid']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {

        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(WeChat::class)->findOneBy(['openid' => $credentials['openid']]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('用户不存在');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Check the user's password or other credentials and return true or false
        // If there are no credentials to check, you can just return true

        if($user->hasRole("ROLE_USER"))
        {
            return true;
        }
        throw new CustomUserMessageAuthenticationException("You don't have permission to access that page.");
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {

        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
        return new RedirectResponse($this->urlGenerator->generate('home_page'));

        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        //throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('app_login');
    }
}
