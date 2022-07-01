<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Test\Integration\Controller\Adminhtml\Index;

use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\TestFramework\TestCase\AbstractBackendController;

/**
 * Class Index
 * @package Convert\Blog\Test\Integration\Controller\Adminhtml\Index
 */
class IndexIntegrationTest extends AbstractBackendController
{
    /**
     * @var string
     */
    protected $resource = 'Convert_Blog::post_list';

    /**
     * @var string
     */
    protected $uri = 'backend/blog/index/index';

    /**
     * @var string
     */
    protected $httpMethod = HttpRequest::METHOD_GET;

    /**
     * @return void
     * @magentoAppArea adminhtml
     */
    public function testCanHandleGetRequests()
    {
        $this->getRequest()->setMethod('GET');
        $this->dispatch('backend/blog/index/index');
        $this->assertSame(200, $this->getResponse()->getHttpResponseCode());
        $this->assertContains('<body ', $this->getResponse()->getBody());
    }

    /**
     * @return void
     * @magentoAppArea adminhtml
     */
    public function testCannotHandlePostRequests()
    {
        $this->getRequest()->setMethod('POST');
        $this->dispatch('backend/blog/index/index');
        $this->assertSame(404, $this->getResponse()->getHttpResponseCode());
        $this->assertContains('adminhtml-noroute-index', $this->getResponse()->getBody());
    }
}
