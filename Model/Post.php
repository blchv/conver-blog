<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Convert\Blog\Api\Data\PostInterface;

/**
 * Class Post
 * @package Convert\Blog\Model
 */
class Post extends AbstractModel implements PostInterface, IdentityInterface
{
    /**
     * Post cache tag
     */
    const CACHE_TAG = 'convert_blog_post';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Post::class);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return bool
     */
    public function getIsDraft()
    {
        return $this->getData(PostInterface::IS_DRAFT);
    }

    /**
     * @return string|null
     */
    public function getUrlKey()
    {
        return $this->getData(PostInterface::URL_KEY);
    }

    /**
     * @inheridoc
     */
    public function getAuthor(): ?string
    {
        return $this->getData(PostInterface::AUTHOR);
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): ?string
    {
        return $this->getData(PostInterface::TITLE);
    }

    /**
     * @inheritdoc
     */
    public function getContent(): ?string
    {
        return $this->getData(PostInterface::CONTENT);
    }

    /**
     * @inheritdoc
     */
    public function getPublishTime(): ?string
    {
        return $this->getData(PostInterface::PUBLISH_TIME);
    }

    /**
     * @inheritdoc
     */
    public function setIsDraft(bool $isDraft)
    {
        $this->setData(PostInterface::IS_DRAFT, $isDraft);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setUrlKey(?string $urlKey)
    {
        $this->setData(PostInterface::URL_KEY, $urlKey);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setAuthor(?string $author): PostInterface
    {
        $this->setData(PostInterface::AUTHOR, $author);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTitle(string $title): PostInterface
    {
        $this->setData(PostInterface::TITLE, $title);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setContent(string $content): PostInterface
    {
        $this->setData(PostInterface::CONTENT, $content);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setPublishTime(?string $publishTime): PostInterface
    {
        $this->setData(PostInterface::PUBLISH_TIME, $publishTime);
        return $this;
    }
}
