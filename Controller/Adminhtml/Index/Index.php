<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Controller\Adminhtml\Index;


use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page as BackendPage;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package Convert\Blog\Controller\Adminhtml\Index
 */
class Index extends BackendAction implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Convert_Blog::post_list';

    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory
    )
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    /**
     * @inheridoc
     */
    public function execute()
    {
        /** @var BackendPage $resultPage */
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Convert_Blog::post_list');
        $resultPage->addBreadcrumb(__('Blog'), __('Blog'));
        $resultPage->addBreadcrumb(__('Manage Posts'), __('Manage Posts'));
        $resultPage->getConfig()->getTitle()->prepend(__('Posts'));

        return $resultPage;
    }
}
