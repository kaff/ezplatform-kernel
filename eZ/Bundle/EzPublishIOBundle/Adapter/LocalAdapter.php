<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Bundle\EzPublishIOBundle\Adapter;

use eZ\Bundle\EzPublishCoreBundle\SiteAccess\Config\ComplexConfigProcessor;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LocalAdapter extends \Oneup\FlysystemBundle\DependencyInjection\Factory\Adapter\LocalFactory
{
    private $complexConfigProcessor;
    
    public function __construct(ComplexConfigProcessor $complexConfigProcessor) {
        $this->complexConfigProcessor = $complexConfigProcessor;
    }
    
    public function create(ContainerBuilder $container, $id, array $config)
    {
        $container
            ->setDefinition($id, new ChildDefinition('oneup_flysystem.adapter.local'))
            ->setLazy($config['lazy'])
            ->replaceArgument(0, $this->getRoot())
            ->replaceArgument(1, $config['writeFlags'])
            ->replaceArgument(2, $config['linkHandling'])
            ->replaceArgument(3, $config['permissions'])
        ;
    }

    private function getRoot()
    {

        return $this->complexConfigProcessor->processComplexSetting('nfs_adapter.local.directory');
    }
}
