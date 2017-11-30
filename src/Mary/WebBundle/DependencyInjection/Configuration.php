<?php

namespace Mary\WebBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mary_web');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        // MaryWebBundle自定义配置项
        $rootNode
            ->children()
                ->arrayNode('uploads')
                    ->info('Upload files configuration')
                    ->isRequired()
                    ->children()
                        ->arrayNode('allowed_mime_types')
                            ->isRequired()
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('groups')
                            ->isRequired()
                            ->prototype('array')
                                ->children()
                                    ->booleanNode('watermark_enabled')
                                        ->isRequired()
                                        ->defaultFalse()
                                    ->end()
                                    ->scalarNode('watermark_target')
                                        ->isRequired()
                                        ->defaultValue('')
                                    ->end()
                                    ->enumNode('watermark_position')
                                        ->isRequired()
                                        ->values(['center', 'top', 'right', 'bottom', 'left', 'top-left', 'top-right', 'bottom-left', 'bottom-right'])
                                    ->end()
                                    ->booleanNode('thumbnail_enabled')
                                        ->isRequired()
                                        ->defaultFalse()
                                    ->end()
                                    ->arrayNode('thumbnail_sizes')
                                        ->isRequired()
                                        ->prototype('array')
                                            ->children()
                                                ->integerNode('width')
                                                    ->cannotBeEmpty()
                                                ->end()
                                                ->integerNode('height')
                                                    ->cannotBeEmpty()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
