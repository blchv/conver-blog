<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Controller\Adminhtml\Post;

use Convert\Blog\Api\Data\PostInterface;
use Convert\Blog\Api\PostRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use RuntimeException;

/**
 * Class InlineEdit
 * @package Convert\Blog\Controller\Adminhtml\Post
 */
class InlineEdit extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Convert_Blog::post_save';

    /**
     * @var PostRepositoryInterface
     */
    protected $postRepository;

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param PostRepositoryInterface $postRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context                 $context,
        PostRepositoryInterface $postRepository,
        JsonFactory             $jsonFactory
    )
    {
        parent::__construct($context);

        $this->postRepository = $postRepository;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function execute(): ResultInterface
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData(
                [
                    'messages' => [__('Please correct the data sent.')],
                    'error' => true,
                ]
            );
        }

        foreach (array_keys($postItems) as $postId) {
            $post = $this->postRepository->getById($postId);
            try {
                $postData = $postItems[$postId];
                $post->setData($postData);
                $this->postRepository->save($post);
            } catch (LocalizedException|RuntimeException $e) {
                $messages[] = $this->getErrorWithPostId($post, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithPostId(
                    $post,
                    __('Something went wrong while saving the page.')->render()
                );
                $error = true;
            }
        }

        return $resultJson->setData(
            [
                'messages' => $messages,
                'error' => $error
            ]
        );
    }

    /**
     * @param PostInterface $post
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithPostId(PostInterface $post, string $errorText): string
    {
        return '[Post ID: ' . $post->getId() . '] ' . $errorText;
    }
}
