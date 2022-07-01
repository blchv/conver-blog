<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Convert\Blog\Model\Post;
use Convert\Blog\Model\ResourceModel\Post as PostResourceModel;
/**
 * Class Collection
 * @package Convert\Blog\Model\ResourceModel\Post
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    /**
     * Define model & resource model
     */
    protected function _construct(): void
    {
        $this->_init(
            Post::class,
            PostResourceModel::class
        );
    }
}
