<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Controller\Adminhtml\Post;

use Convert\Blog\Api\PostRepositoryInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;

/**
 * Class Delete
 * @package Convert\Blog\Controller\Adminhtml\Post
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Convert_Blog::post_save';

    /**
     * @var PostRepositoryInterface
     */
    protected $postRepository;

    /**
     * Delete constructor.
     * @param PostRepositoryInterface $postRepository
     * @param Context $context
     */
    public function __construct(
        PostRepositoryInterface $postRepository,
        Context                 $context
    )
    {
        $this->postRepository = $postRepository;
        parent::__construct($context);
    }

    /**
     * @InheritDoc
     */
    public function execute()
    {
        $postId = $this->getRequest()->getParam('entity_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($postId) {
            try {
                $this->postRepository->deleteById((int) $postId);
                $this->messageManager->addSuccessMessage(__('Post deleted successfully.'));
                return $resultRedirect->setPath('blog/index/index/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $postId]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find the that you\'re trying to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
