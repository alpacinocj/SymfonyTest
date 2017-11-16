<?php

namespace Mary\WebBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Mary\WebBundle\Service\AssetsService;
use Mary\WebBundle\Service\UploaderService;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class MaryWebExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // 添加Bundle常用类并编译, 减少I/O操作
        // 3.2新增 使用 patterns 来添加待编译类的选项自 Symfony 3.2 起被引入。
        $this->addClassesToCompile([
            // 'MaryWebBundle\\Service\\',
            AssetsService::class,
            UploaderService::class
        ]);
    }
}
