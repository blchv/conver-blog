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
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Convert\Blog\Model\ResourceModel\Post\CollectionFactory;

/**
 * Class MassDelete
 * @package Convert\Blog\Controller\Adminhtml\Post
 */
class MassDelete extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Convert_Blog::post_save';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        PostRepositoryInterface $postRepository
    )
    {
        parent::__construct($context);

        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->postRepository = $postRepository;
    }

    /**
     * @return Redirect
     * @throws LocalizedException|Exception
     */
    public function execute(): Redirect
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $post) {
            $this->postRepository->delete($post);
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('blog/index/index');
    }
}
