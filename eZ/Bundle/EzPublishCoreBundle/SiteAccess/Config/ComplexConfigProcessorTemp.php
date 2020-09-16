<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace eZ\Bundle\EzPublishCoreBundle\SiteAccess\Config;

final class ComplexConfigProcessorTemp
{
    private $complexConfigProcessor;

    private $paramName = 'nfs_adapter.local.directory';

    public function __construct(
        $paramName,
        ComplexConfigProcessor $complexConfigProcessor
    ) {
        $this->complexConfigProcessor = $complexConfigProcessor;
    }
    
    public function __toString(): string
    {
        return $this->complexConfigProcessor->processComplexSetting($this->paramName);
    }
}
