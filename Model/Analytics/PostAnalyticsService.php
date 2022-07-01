<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Model\Analytics;


use Convert\Blog\Model\ResourceModel\Post as PostResourceModel;

/**
 * Class PostAnalyticsService
 * @package Convert\Blog\Model\Analytics
 */
class PostAnalyticsService
{
    /**
     * @var PostResourceModel
     */
    private $postResourceModel;
    /**
     * @var AnalyticsBackend
     */
    private $analyticsBackend;

    /**
     * @param PostResourceModel $postResourceModel
     * @param AnalyticsBackend $analyticsBackend
     */
    public function __construct(
        PostResourceModel $postResourceModel,
        AnalyticsBackend $analyticsBackend
    )
    {
        $this->postResourceModel = $postResourceModel;
        $this->analyticsBackend = $analyticsBackend;
    }

    /**
     * @param int $postId
     * @return false
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function ingest(int $postId)
    {
        $this->analyticsBackend->incrementViewCount($postId);

        return false;
    }

    /**
     * @param int $postId
     * @return int|null
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getViewCountForPost(int $postId): ?int
    {
        return $this->analyticsBackend->parseViewCount($postId);
    }
}
