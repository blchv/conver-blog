<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Test\Integration\Controller\Post;


/**
 * Class Index
 * @package Convert\Blog\Test\Integration\Controller\Post
 */
class Index
{
    public function testCms()
    {
        /**
         * @var \Magento\Cms\Model\Page $page
         */
//        $page = ObjectManager::getInstance()->create(\Magento\Cms\Model\PageFactory::class)->create();
//        $pageRepository = ObjectManager::getInstance()->create(\Magento\Cms\Api\PageRepositoryInterface::class);
//        $page->setTitle('Test page');
//        $pageRepository->save($page);


        $this->getRequest()->setMethod('GET');
        $this->dispatch('blog/salad-essentially-food-rabbits');
        $this->assertSame(200, $this->getResponse()->getHttpResponseCode());
        $this->assertContains('<body ', $this->getResponse()->getBody());
    }
}
