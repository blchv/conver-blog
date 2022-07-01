<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\ViewModel;


use Convert\Blog\Api\Data\PostInterface;
use Convert\Blog\Api\PostRepositoryInterface;
use Convert\Blog\Helper\Logger;
use Convert\Blog\Model\Config\Provider;
use Convert\Blog\Model\Data\AuthorResolver;
use Convert\Blog\Model\Data\PublishDateResolver;
use Convert\Blog\Model\PostUrlGenerator;
use Convert\Blog\Model\ResourceModel\Post\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class PostDataProvider
 * @package Convert\Blog\ViewModel
 */
class PostDataProvider implements ArgumentInterface
{
    /**
     * @var ?PostInterface
     */
    private $post;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var AuthorResolver
     */
    private $authorResolver;
    /**
     * @var PublishDateResolver
     */
    private $publishDateResolver;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var Provider
     */
    private $configProvider;
    /**
     * @var PostUrlGenerator
     */
    private $postUrlGenerator;

    /**
     * @param RequestInterface $request
     * @param PostRepositoryInterface $postRepository
     * @param Logger $logger
     * @param AuthorResolver $authorResolver
     * @param PublishDateResolver $publishDateResolver
     * @param CollectionFactory $collectionFactory
     * @param Provider $configProvider
     * @param PostUrlGenerator $postUrlGenerator
     */
    public function __construct(
        RequestInterface    $request,
        PostRepositoryInterface $postRepository,
        Logger $logger,
        AuthorResolver $authorResolver,
        PublishDateResolver $publishDateResolver,
        CollectionFactory $collectionFactory,
        Provider $configProvider,
        PostUrlGenerator $postUrlGenerator
    )
    {
        $this->post = null;
        $this->request = $request;
        $this->postRepository = $postRepository;
        $this->logger = $logger;
        $this->authorResolver = $authorResolver;
        $this->publishDateResolver = $publishDateResolver;
        $this->collectionFactory = $collectionFactory;
        $this->configProvider = $configProvider;
        $this->postUrlGenerator = $postUrlGenerator;
    }

    /**
     * @return int
     */
    public function getCurrentPostId(): int
    {
        return $this->post->getId();
    }

    /**
     * @return string
     */
    public function getCurrentTitle(): string
    {
        return $this->post->getTitle();
    }

    /**
     * @return string
     */
    public function getCurrentContent(): string
    {
        return $this->post->getContent();
    }

    /**
     * @return string
     */
    public function getCurrentAuthor(): string
    {
        return $this->authorResolver->resolve(
            $this->post->getAuthor()
        );
    }

    /**
     * @return string
     */
    public function getCurrentPublishTime(): string
    {
        return $this->publishDateResolver->resolve(
            $this->post->getPublishTime()
        );
    }

    public function getAllPosts(): array
    {
        /**
         * @var \Convert\Blog\Model\ResourceModel\Post\Collection $collection
         */
        $collection = $this->collectionFactory->create();

        $collection->getSelect()->order("{$this->configProvider->provideDefaultSorting()} ASC");
        $collection->addFilter('is_draft', 0);

        return $collection->getItems();
    }

    /**
     * @param PostInterface $post
     * @return void
     */
    public function setPost(PostInterface $post)
    {
        $this->post = $post;
    }

    /**
     * @return void
     */
    public function loadPostFromRequest()
    {
        $this->post = $this->loadPost(
            (int)$this->request->getParam('entity_id')
        );
    }

    /**
     * @return bool
     */
    public function hasPost(): bool
    {
        return !empty($this->post);
    }

    /**
     * @param int|null $postId
     * @return PostInterface|null
     */
    private function loadPost(?int $postId): ?PostInterface
    {
        if (!empty($postId)) {
            try {
                return $this->postRepository->getById($postId);
            } catch (LocalizedException $e) {
                $message = __("Failed to load post with ID: %1. Original error message is: %2 ", $postId, $e->getMessage())->render();
                $this->logger->debug("$message (in) " . __FILE__  . ":" . __LINE__);
            }
        }

        return null;
    }

    /**
     * @param PostInterface $post
     * @return string
     */
    public function getUrlForPost(PostInterface $post): string
    {
        return $this->postUrlGenerator->getFullUrl($post);
    }
}
