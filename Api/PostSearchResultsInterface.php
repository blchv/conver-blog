<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Api;

use Magento\Framework\Api\SearchResultsInterface;
use Convert\Blog\Api\Data\PostInterface;

interface PostSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get post list.
     *
     * @return PostInterface[]
     */
    public function getItems();

    /**
     * Set post list.
     *
     * @param PostInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
