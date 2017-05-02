<?php

namespace RValin\InfoBipBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class RValinInfoBipExtension extends Extension
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


        $def = $container->getDefinition('r_valin_info_bip.sms_manager');
        $def->replaceArgument(1, $config['from']);
        $def->replaceArgument(2, $config['user']);
        $def->replaceArgument(3, $config['password']);
        $def->replaceArgument(4, $config['delivery_number']);
        $def->replaceArgument(5, $config['send_sms']);
    }
}
