<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\ViewModel;

use Convert\Blog\Model\Analytics\PostAnalyticsService;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class PostAnalitycs
 * @package Convert\Blog\ViewModel
 */
class PostAnalytics implements ArgumentInterface
{
    const FRONTEND_ENDPOINT = 'rest/default/V1/post/analytics/';
    /**
     * @var PostAnalyticsService
     */
    private $analyticsService;
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param PostAnalyticsService $analyticsService
     * @param RequestInterface $request
     */
    public function __construct(
        PostAnalyticsService $analyticsService,
        RequestInterface $request
    )
    {
        $this->analyticsService = $analyticsService;
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return self::FRONTEND_ENDPOINT;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getViewForPostByRequest(): string
    {
        $viewCount = $this->analyticsService->getViewCountForPost(
            (int) $this->request->getParam('entity_id')
        );

        return $viewCount ? (string) $viewCount : __('No views so far :(')->render();
    }
}
