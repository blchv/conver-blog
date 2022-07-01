<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Model;

use Exception;
use Convert\Blog\Api\Data\PostInterface;
use Convert\Blog\Api\PostSearchResultsInterfaceFactory;
use Convert\Blog\Api\PostSearchResultsInterface;
use Convert\Blog\Api\PostRepositoryInterface;
use Convert\Blog\Model\ResourceModel\Post as PostResourceModel;
use Convert\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;


/**
 * Class PostRepository
 * @package Convert\Blog\Model
 */
class PostRepository implements PostRepositoryInterface
{
    /**
     * @var PostResourceModel
     */
    private $resource;
    /**
     * @var PostFactory
     */
    private $postFactory;
    /**
     * @var PostCollectionFactory
     */
    private $postCollectionFactory;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * @var PostSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @param PostResourceModel $resource
     * @param PostFactory $postFactory
     * @param PostCollectionFactory $postCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param PostSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        PostResourceModel $resource,
        PostFactory $postFactory,
        PostCollectionFactory $postCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        PostSearchResultsInterfaceFactory $searchResultsFactory
    )
    {
        $this->resource = $resource;
        $this->postFactory = $postFactory;
        $this->postCollectionFactory = $postCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @param PostInterface $post
     * @return PostInterface
     * @throws CouldNotSaveException
     */
    public function save(PostInterface $post): PostInterface
    {
        try {
            $this->resource->save($post);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the post: %1', $exception->getMessage()),
                $exception
            );
        }
        return $post;
    }

    /**
     * @param int $postId
     * @return PostInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $postId): PostInterface
    {
        $post = $this->postFactory->create();
        $post->load($postId);

        if (!$post->getId()) {
            throw new NoSuchEntityException(__('The post with ID "%1" doesn\'t exist.', $postId));
        }

        return $post;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): PostSearchResultsInterface
    {
        $postCollection = $this->postCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $postCollection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($postCollection->getItems());
        $searchResults->setTotalCount($postCollection->getSize());

        return $searchResults;
    }

    /**
     * @return array|PostInterface[]
     */
    public function getAllPublicPosts(): array
    {
        $postCollection = $this->postCollectionFactory->create();
        $postCollection->addFilter('is_draft', '0');
        return $postCollection->getItems();
    }

    /**
     * @param PostInterface $post
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(PostInterface $post): bool
    {
        try {
            $this->resource->delete($post);
        } catch (Exception $e) {
            throw new CouldNotDeleteException(
                __('Could not delete the post: %1', $e->getMessage())
            );
        }

        return true;
    }

    /**
     * @param int $postId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $postId): bool
    {
        return $this->delete($this->getById($postId));
    }
}
