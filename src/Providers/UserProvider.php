<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/9
 * Time: 17:31
 * Site: http://www.drupai.com
 */

namespace App\Providers;


use App\Contracts\WechatUserProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserProvider implements WechatUserProvider
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * AuthenticationHandler constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * 根据openid获取用户
     * @param $openid
     * @return \App\Entity\WeChat|null|object
     */
    public function find($openid)
    {
        return $this->container->get('doctrine.orm.default_entity_manager')
            ->getRepository('App:WeChat')
            ->findOneBy(['openid' => $openid]);
    }
}