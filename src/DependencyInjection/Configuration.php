<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/9
 * Time: 18:00
 * Site: http://www.drupai.com
 */

namespace App\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wechat');

        $rootNode
            ->children()

            ->arrayNode('security')
            ->children()
            ->booleanNode('enabled')->isRequired()->end()
            ->scalarNode('user_provider_id')->isRequired()->end()
            ->end()
            ->end()

            ->arrayNode('cache')
            ->children()
            ->booleanNode('overwrite')->isRequired()->end()
            ->scalarNode('cache_id')->isRequired()->end()
            ->end()
            ->end()

            ->scalarNode('service_alias')->end()
            ->end()
        ;

        return $treeBuilder;
    }


}
{

}