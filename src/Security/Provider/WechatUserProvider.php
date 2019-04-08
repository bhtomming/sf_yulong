<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/6
 * Time: 17:59
 * Site: http://www.drupai.com
 */

namespace App\Security\Provider;


use Symfony\Component\DependencyInjection\ContainerInterface;

class WechatUserProvider implements \App\Contracts\WechatUserProvider
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

    public function find($openid)
    {
        return $this->container->get('doctrine.orm.default_entity_manager')
            ->getRepository('App:WeChat')
            ->findOneBy(['openid' => $openid]);
    }
}