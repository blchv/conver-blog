<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Test\Integration;

use Convert\Blog\Api\Data\PostInterface;
use Convert\Blog\Model\Post;
use Convert\Blog\Model\PostFactory;
use Convert\Blog\Model\PostRepository;
use Convert\Blog\Model\ResourceModel\Post as PostResourceModel;
use Convert\Blog\Model\ResourceModel\Post\Collection as PostCollection;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
/**
 * @magentoDbIsolation enabled
 */
class PostEntityTest extends TestCase
{
    /**
     * @return Post
     */
    private function instantiatePost(): Post
    {
        return ObjectManager::getInstance()->create(PostFactory::class)->create();
    }

    /**
     * @return PostResourceModel
     */
    private function instantiateResourceModel(): PostResourceModel
    {
        return ObjectManager::getInstance()->create(PostResourceModel::class);
    }

    /**
     * @return PostRepository
     */
    private function instantiatePostRepository(): PostRepository
    {
        return ObjectManager::getInstance()->create(PostRepository::class);
    }

    /**
     * @param string $title
     * @param string $content
     * @param bool $isDraft
     * @param string $urlKey
     * @param int|null $authorId
     * @param string|null $publishTime
     * @return Post
     * @throws AlreadyExistsException
     */
    private function createPost(
        string $title = 'Test post title',
        string $content = 'Test post content',
        bool $isDraft = false,
        string $urlKey = 'generate',
        ?int $authorId = null,
        ?string $publishTime = null
    ): Post
    {
        if ($urlKey === 'generate') {
            $urlKey = substr(md5(microtime()), rand(0, 26), 5);
        }

        $post = $this->instantiatePost();

        $post->setIsDraft($isDraft)
            ->setUrlKey($urlKey)
            ->setAuthorId($authorId)
            ->setTitle($title)
            ->setContent($content)
            ->setPublishTime($publishTime);

        $this->instantiateResourceModel()->save($post);
        return $post;
    }

    /**
     * @return void
     * @throws AlreadyExistsException
     */
    public function testCanSaveAndLoad()
    {
        $post = $this->createPost();

        $loadedPost = $this->instantiatePost();
        $this->instantiateResourceModel()->load($loadedPost, $post->getId());

        $this->assertSame($post->getId(), $loadedPost->getId());
        $this->assertSame($post->getTitle(), $loadedPost->getTitle());
        $this->assertSame($post->getContent(), $loadedPost->getContent());
        $this->assertSame(null, $post->getAuthorId());
    }

    /**
     * @throws AlreadyExistsException
     */
    public function testCanLoadMultiplePosts()
    {
        $postA = $this->createPost('Post A Title', 'Post A Content');
        $postB = $this->createPost('Post B Title', 'Post B Content');

        /**
         * @var PostCollection $collection
         */
        $collection = ObjectManager::getInstance()->create(PostCollection::class);

        $this->assertContains($postA->getId(), array_keys($collection->getItems()));
        $this->assertContains($postB->getId(), array_keys($collection->getItems()));
    }

    /**
     * @return void
     * @throws AlreadyExistsException
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function testCanSaveAndLoadWithRepository()
    {
        $postRepository = $this->instantiatePostRepository();

        $post = $this->createPost();

        $savedPost = $postRepository->save($post);

        $loadedPost = $postRepository->getById($post->getId());

        $this->assertInstanceOf(PostInterface::class, $savedPost);
        $this->assertSame($loadedPost->getId(), $post->getId());
    }

    /**
     * @return void
     * @throws AlreadyExistsException
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function testCanSaveAndDeleteWithRepository() {
        $this->expectException(NoSuchEntityException::class);

        $post = $this->createPost();
        $postRepository = $this->instantiatePostRepository();
        $postRepository->save($post);

        $postRepository->deleteById($post->getId());

        $postRepository->getById($post->getId());
    }

    public function testCanLoadListWithRepository()
    {
        $postRepository = $this->instantiatePostRepository();
        $postA = $this->createPost();
        $postB = $this->createPost();

        $postRepository->save($postA);
        $postRepository->save($postB);

        /**
         * @var SearchCriteriaBuilder $searchCriteriaBuilder
         */
        $searchCriteriaBuilder = ObjectManager::getInstance()->create(SearchCriteriaBuilder::class);

        $searchCriteria =$searchCriteriaBuilder->addFilter(
            PostInterface::ENTITY_ID,
            [$postA->getId(), $postB->getId()],
            'in')
            ->create();

        $postList = $postRepository->getList($searchCriteria)->getItems();

        $this->assertContains($postA->getId(), array_keys($postList));
        $this->assertContains($postB->getId(), array_keys($postList));
    }

    public function testCanLoadAllPublicPostsWithRepository()
    {
        $postRepository = $this->instantiatePostRepository();
        $postA = $this->createPost();
        $postB = $this->createPost(
            'Draft post',
            'Draft contet',
            true
        );

        $postRepository->save($postA);
        $postRepository->save($postB);

        /**
         * @var SearchCriteriaBuilder $searchCriteriaBuilder
         */
        $searchCriteriaBuilder = ObjectManager::getInstance()->create(SearchCriteriaBuilder::class);

        $searchCriteria =$searchCriteriaBuilder
            ->addFilter(PostInterface::IS_DRAFT, '0')
            ->create();

        $postList = $postRepository->getList($searchCriteria)->getItems();

        $this->assertContains($postA->getId(), array_keys($postList));
        $this->assertNotContains($postB->getId(), array_keys($postList));
    }
}
