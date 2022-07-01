<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Api;

use Convert\Blog\Api\PostSearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use Convert\Blog\Api\Data\PostInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface PostRepositoryInterface
{
    /**
     * @param PostInterface $post
     * @return PostInterface
     * @throws LocalizedException
     */
    public function save(PostInterface $post): PostInterface;

    /**
     * @param int $postId
     * @return PostInterface
     * @throws LocalizedException
     */
    public function getById(int $postId): PostInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return PostSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): PostSearchResultsInterface;

    /**
     * @return PostInterface[]
     */
    public function getAllPublicPosts(): array;

    /**
     * @param PostInterface $post
     * @return bool
     * @throws LocalizedException
     */
    public function delete(PostInterface $post): bool;

    /**
     * @param int $postId
     * @return bool
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById(int $postId): bool;
}
