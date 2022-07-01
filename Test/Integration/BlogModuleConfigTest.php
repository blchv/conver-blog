<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Test\Integration;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\DeploymentConfig\Reader as DeploymentConfigReader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Module\ModuleList;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

class BlogModuleConfigTest extends TestCase
{
    private $moduleName = 'Convert_Blog';

    public function testTheModuleIsRegistered()
    {
        $registrar = new ComponentRegistrar();
        $this->assertArrayHasKey(
            $this->moduleName,
            $registrar->getPaths(ComponentRegistrar::MODULE)
        );
    }

    public function testTheModuleIsConfiguredAndEnabledInTestEnvironment()
    {
        $objectManager = ObjectManager::getInstance();

        $moduleList = $objectManager->create(ModuleList::class);

        $this->assertTrue($moduleList->has($this->moduleName), 'Module not enabled in test env.');
    }

    public function testTheModuleIsConfiguredAndEnabledInRealEnvironment()
    {
        $objectManager = ObjectManager::getInstance();

        $directoryList = $objectManager->create(DirectoryList::class, ['root' => BP]);
        $configReader = $objectManager->create(DeploymentConfigReader::class, ['dirList' => $directoryList]);
        $deploymentConfig = $objectManager->create(DeploymentConfig::class, ['reader' => $configReader]);

        $moduleList = $objectManager->create(ModuleList::class, ['config' => $deploymentConfig]);

        $this->assertTrue($moduleList->has($this->moduleName), 'Module not enabled in real env.');
    }
}
