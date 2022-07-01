<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Controller\Adminhtml\Post;

use Convert\Blog\Api\Data\PostInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Convert\Blog\Api\PostRepositoryInterface;
use Convert\Blog\Model\PostFactory;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class Save
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Convert_Blog::post_save';

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @var PostFactory
     */
    private $postFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param PostRepositoryInterface $postRepository
     * @param PostFactory $postFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        Action\Context          $context,
        DataPersistorInterface  $dataPersistor,
        PostRepositoryInterface $postRepository,
        PostFactory             $postFactory,
        ManagerInterface        $messageManager
    )
    {
        parent::__construct($context);

        $this->dataPersistor = $dataPersistor;
        $this->postRepository = $postRepository;
        $this->postFactory = $postFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * @return Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            if (isset($data['is_draft']) && $data['is_draft'] === 'true') {
                $data['is_draft'] = true;
            }
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }

            /** @var PostInterface $post */
            $post = $this->postFactory->create();

            $postId = $this->getRequest()->getParam('entity_id');
            if ($postId) {
                $post = $this->postRepository->getById((int) $postId);
            }

            $post->setData($data);

            try {
                $this->postRepository->save($post);
                $this->messageManager->addSuccessMessage(__('The post was saved successfully.'));
                $this->dataPersistor->clear('convert_blog_post');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $post->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('blog/index/index');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the data.'));
            }

            $this->dataPersistor->set('convert_blog_post', $data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }

        return $resultRedirect->setPath('blog/index/index');
    }
}
