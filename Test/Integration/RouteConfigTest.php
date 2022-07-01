<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Test\Integration;


use Convert\Blog\Controller\Index\Index as IndexController;
use Convert\Blog\Controller\Adminhtml\Index\Index as AdminIndexController;
use Magento\Framework\App\Route\ConfigInterface as RouteConfigInterface;
use Magento\Framework\App\Router\Base as BaseRouter;
use Magento\TestFramework\ObjectManager;
use Magento\TestFramework\Request;
use PHPUnit\Framework\TestCase;

/**
 * Class RouteConfigTest
 * @package Convert\Blog\Test\Integration
 */
class RouteConfigTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp() {
        $this->objectManager = ObjectManager::getInstance();
    }

    /**
     * @return void
     * @magentoAppArea frontend
     */
    public function testFrontendFrontNameIsRegistered()
    {
        /** @var RouteConfigInterface $routeConfig */
        $routeConfig = $this->objectManager->create(RouteConfigInterface::class);
        $this->assertContains('Convert_Blog', $routeConfig->getModulesByFrontName('blog'), 'Frontend route not defined.');
    }

    /**
     * @return void
     * @magentoAppArea frontend
     */
    public function testFrontendIndexActionCanBeFound()
    {
        $request = $this->objectManager->create(Request::class);
        /** @var Request $request */
        $request->setModuleName('blog');
        $request->setControllerName('index');
        $request->setActionName('index');

        /** @var BaseRouter $baseRouter */
        $baseRouter = $this->objectManager->create(BaseRouter::class);

        $expectedAction = IndexController::class;
        $this->assertInstanceOf($expectedAction, $baseRouter->match($request), 'Frontend index controller cannot be found');
    }

    /**
     * @return void
     * @magentoAppArea adminhtml
     */
    public function testAdminhtmlFrontNameIsRegistered()
    {
        /** @var RouteConfigInterface $routeConfig */
        $routeConfig = $this->objectManager->create(RouteConfigInterface::class);
        $this->assertContains('Convert_Blog', $routeConfig->getModulesByFrontName('blog'), 'Frontend route not defined.');
    }

    /**
     * @return void
     * @magentoAppArea frontend
     */
    public function testAdminhtmlIndexActionCanBeFound()
    {
        $request = $this->objectManager->create(Request::class);
        /** @var Request $request */
        $request->setModuleName('blog');
        $request->setControllerName('index');
        $request->setActionName('index');

        /** @var BaseRouter $baseRouter */
        $baseRouter = $this->objectManager->create(\Magento\Backend\App\Router::class);

        $expectedAction = AdminIndexController::class;
        $this->assertInstanceOf($expectedAction, $baseRouter->match($request), 'Frontend index controller cannot be found');
    }
}
