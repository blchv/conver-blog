<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

declare(strict_types=1);

namespace Convert\Blog\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class SortBy
 * @package Convert\Blog\Model\Config\Source
 */
class SortBy implements OptionSourceInterface
{

    /**
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'entity_id', 'label' => __('Post id')],
            ['value' => 'title', 'label' => __('Title')],
            ['value' => 'author', 'label' => __('Author')],
            ['value' => 'content', 'label' => __('Content')]];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'entity_id' => __('Post id'),
            'title' => __('Title'),
            'author' => __('Author'),
            'content' => __('Content')
        ];
    }
}
