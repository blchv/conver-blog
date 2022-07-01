<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Class Edit
 * @package Convert\Blog\Controller\Adminhtml\Post
 */
class Edit extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Convert_Blog::post_save';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context     $context,
        PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return Page
     */
    public function execute(): Page
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Convert_Blog::post_save')
            ->addBreadcrumb(__('Blog'), __('Blog'))
            ->addBreadcrumb(__('Manage Posts'), __('Manage Posts'))
            ->getConfig()->getTitle()->prepend(__('Edit Post'));

        return $resultPage;
    }
}
