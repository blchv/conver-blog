<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Model\Data;

use Convert\Blog\Model\Config\Provider;

/**
 * Class AuthorResolver
 * @package Convert\Blog\Model\Author
 */
class AuthorResolver
{
    /**
     * @var Provider
     */
    private $configProvider;

    /**
     * @param Provider $configProvider
     */
    public function __construct(
        Provider $configProvider
    )
    {
        $this->configProvider = $configProvider;
    }

    /**
     * @param string|null $author
     * @return string
     */
    public function resolve(?string $author): string
    {
        if (is_null($author)) {
            return $this->configProvider->provideDefaultAuthor();
        }

        return $author;
    }
}
