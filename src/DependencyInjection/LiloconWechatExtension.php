<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/9
 * Time: 18:02
 * Site: http://www.drupai.com
 */

namespace App\DependencyInjection;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class LiloconWechatExtension  extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('lilocon.security.enabled', $config['security']['enabled']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $sdk_definition = $container->getDefinition('lilocon.wechat.sdk');


        // 开启微信授权功能
        if ($config['security']['enabled']) {
            $loader->load('security_services.xml');
            $container->getDefinition('lilocon.security.authentication.wechat_provider')
                ->replaceArgument(0, new Reference($config['security']['user_provider_id']));
        }

        // 自定义缓存
        if ($config['cache']['overwrite']) {
            $sdk_definition->replaceArgument(2, new Reference($config['cache']['cache_id']));
        }

        // service_alias
        if (array_key_exists('service_alias', $config)) {
            $container->setAlias($config['service_alias'], 'lilocon.wechat.sdk');
        }
    }
}