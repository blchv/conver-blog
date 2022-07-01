<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

declare(strict_types=1);

namespace Convert\Blog\Block\Adminhtml\Post\Edit;

use Magento\Backend\Block\Widget\Context;

/**
 * Class GenericButton
 * @package Convert\Blog\Block\Adminhtml\Post\Edit
 */
class GenericButton
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    )
    {
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getBackUrl(): string
    {
        return $this->getUrl('blog/index/index');
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }

    /**
     * @return string
     */
    public function getDeleteUrl(): string
    {
        return $this->getUrl('*/*/delete', ['entity_id' => $this->getPostId()]);
    }

    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->context->getRequest()->getParam('entity_id');
    }
}
