<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Api\Data;

interface PostInterface
{
    /**
     * Data keys
     */

    const ENTITY_ID = 'entity_id';
    const IS_DRAFT = 'is_draft';
    const URL_KEY = 'url_key';
    const AUTHOR = 'author';
    const TITLE = 'title';
    const CONTENT = 'content';
    const PUBLISH_TIME = 'publish_time';

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @return bool
     */
    public function getIsDraft();

    /**
     * @return string|null
     */
    public function getUrlKey();

    /**
     * @return string|null
     */
    public function getAuthor(): ?string;

    /**
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * @return string|null
     */
    public function getPublishTime(): ?string;

    /**
     * @param mixed $value
     * @return PostInterface
     */
    public function setId($value);

    /**
     * @param bool $isDraft
     * @return PostInterface
     */
    public function setIsDraft(bool $isDraft);

    /**
     * @param string|null $urlKey
     * @return PostInterface
     */
    public function setUrlKey(?string $urlKey);

    /**
     * @param string|null $author
     * @return PostInterface
     */
    public function setAuthor(?string $author): PostInterface;

    /**
     * @param string $title
     * @return PostInterface
     */
    public function setTitle(string $title): PostInterface;

    /**
     * @param string $content
     * @return PostInterface
     */
    public function setContent(string $content): PostInterface;

    /**
     * @param string|null $publishTime
     * @return PostInterface
     */
    public function setPublishTime(?string $publishTime): PostInterface;
}
