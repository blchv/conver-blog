<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

declare(strict_types=1);

namespace Convert\Blog\Block\Adminhtml\Post\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 * @package Convert\Blog\Block\Adminhtml\Post\Edit
 */
class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        if (!$this->getPostId()) return [];

        return [
            'label' => __('Delete Post'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm( \'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\')',
            'sort_order' => 20,
        ];
    }
}
