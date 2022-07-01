<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Test\Integration\Controller\Index;


use Magento\TestFramework\TestCase\AbstractController;
use Magento\TestFramework\ObjectManager;
/**
 * Class IndexIntegrationTest
 * @package Convert\Blog\Test\Integration\Controller\Index
 */
class IndexIntegrationTest extends AbstractController
{
    /**
     * @return void
     */
    public function testCanHandleGetRequests()
    {
        $this->getRequest()->setMethod('GET');
        $this->dispatch('blog/index/index');
        $this->assertSame(200, $this->getResponse()->getHttpResponseCode());
        $this->assertContains('<body ', $this->getResponse()->getBody());
    }

    /**
     * @return void
     */
    public function testCannotHandlePostRequest()
    {
        $this->getRequest()->setMethod('POST');
        $this->dispatch('blog/index/index');
        $this->assertSame(404, $this->getResponse()->getHttpResponseCode());
        $this->assert404NotFound();
    }
}
