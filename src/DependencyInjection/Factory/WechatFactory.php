<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/5
 * Time: 23:37
 * Site: http://www.drupai.com
 */

namespace App\DependencyInjection\Factory;


use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class WechatFactory implements SecurityFactoryInterface
{

    /**
     * Configures the container services required to use the authentication listener.
     *
     * @param ContainerBuilder $container
     * @param string $id The unique id of the firewall
     * @param array $config The options array for the listener
     * @param string $userProvider The service id of the user provider
     * @param string $defaultEntryPoint
     *
     * @return array containing three values:
     *               - the provider id
     *               - the listener id
     *               - the entry point id
     */
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.wechat.'.$id;
        $container
            ->setDefinition($providerId, new ChildDefinition('security.authentication.wechat_provider'))
        ;

        $listenerId = 'security.authentication.listener.wechat.'.$id;
        $container->setDefinition(
            $listenerId,
            new ChildDefinition('security.authentication.wechat_listener')
        );

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    /**
     * Defines the position at which the provider is called.
     * Possible values: pre_auth, form, http, and remember_me.
     *
     * @return string
     */
    public function getPosition()
    {
        return 'pre_auth';
    }

    /**
     * Defines the configuration key used to reference the provider
     * in the firewall configuration.
     *
     * @return string
     */
    public function getKey()
    {
        return 'wechat_login';
    }

    public function addConfiguration(NodeDefinition $builder)
    {
        $builder
            ->children()
            ->scalarNode('authorize_path')->isRequired()->end()
            ->scalarNode('default_redirect')->end()
            ->end()
        ;
    }
}