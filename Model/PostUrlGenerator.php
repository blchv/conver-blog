<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Model;


use Convert\Blog\Api\Data\PostInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\Filter\FilterManager;

/**
 * Class PostUrlGenerator
 * @package Convert\Blog\Model
 */
class PostUrlGenerator
{
    const PREFIX = 'blog/post/';

    /**
     * @var FilterManager
     */
    private $filterManager;
    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @param FilterManager $filterManager
     * @param UrlInterface $url
     */
    public function __construct(
        FilterManager $filterManager,
        UrlInterface $url
    ) {
        $this->filterManager = $filterManager;
        $this->url = $url;
    }

    /**
     * @param PostInterface $post
     * @return string
     */
    public function getFullUrl(PostInterface $post): string
    {
        return $this->url->getBaseUrl() . self::PREFIX . $post->getUrlKey();
    }

    /**
     * @param PostInterface $post
     * @return string
     */
    public function generateUrl(PostInterface $post): string
    {
        $urlKey = $post->getUrlKey();

        return $this->filterManager->translitUrl(empty($urlKey) ? $post->getTitle() : $urlKey);
    }

    /**
     * @param PostInterface $post
     * @return string
     */
    public function getCanonicalUrlPath(PostInterface $post): string
    {
        return 'blog/post/view/entity_id/' . $post->getId();
    }
}
