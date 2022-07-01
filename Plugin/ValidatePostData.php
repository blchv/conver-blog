<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Plugin;

use Convert\Blog\Api\Data\PostInterface;
use Convert\Blog\Model\PostUrlGenerator;
use Convert\Blog\Model\Config\Provider;
use Convert\Blog\Model\ResourceModel\Post as PostResourceModel;

/**
 * Class Post
 * @package Convert\Blog\Plugin\Blog\Model\ResourceModel
 */
class ValidatePostData
{
    /**
     * @var PostUrlGenerator
     */
    private $postUrlGenerator;

    /**
     * @var Provider
     */
    private $configProvider;

    /**
     * @param PostUrlGenerator $postUrlGenerator
     * @param Provider $configProvider
     */
    public function __construct(
        PostUrlGenerator $postUrlGenerator,
        Provider         $configProvider
    ) {
        $this->postUrlGenerator = $postUrlGenerator;
        $this->configProvider = $configProvider;
    }

    /**
     * @param PostResourceModel $subject
     * @param PostInterface $post
     * @return void
     */
    public function beforeSave(
        PostResourceModel $subject,
        PostInterface     $post
    ) {
        if (empty($post->getUrlKey())) {
            $post->setUrlKey($this->postUrlGenerator->generateUrl($post));
        }

        if (empty($post->getAuthor())) {
            $post->setAuthor($this->configProvider->provideDefaultAuthor());
        }
    }
}
