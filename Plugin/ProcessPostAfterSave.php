<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Plugin;

use Convert\Blog\Api\Data\PostInterface;
use Convert\Blog\Model\PostUrlRewriteGenerator;
use Magento\UrlRewrite\Model\Exception\UrlAlreadyExistsException;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Class ProcessPostAfterSave
 * @package Convert\Blog\Plugin
 */
class ProcessPostAfterSave
{
    /**
     * @var UrlPersistInterface
     */
    private $urlPersist;

    /**
     * @var PostUrlRewriteGenerator
     */
    private $postUrlRewriteGenerator;

    /**
     * @param PostUrlRewriteGenerator $postUrlRewriteGenerator
     * @param UrlPersistInterface $urlPersist
     */
    public function __construct(
        PostUrlRewriteGenerator $postUrlRewriteGenerator,
        UrlPersistInterface     $urlPersist
    )
    {
        $this->urlPersist = $urlPersist;
        $this->postUrlRewriteGenerator = $postUrlRewriteGenerator;
    }

    /**
     * @param PostInterface $subject
     * @param PostInterface $result
     * @return void
     * @throws UrlAlreadyExistsException
     */
    public function afterAfterSave(
        PostInterface $subject,
        PostInterface $result
    )
    {
        if ($subject->dataHasChangedFor('url_key')) {
            $urls = $this->postUrlRewriteGenerator->generate($result);

            $this->urlPersist->deleteByData([
                UrlRewrite::ENTITY_ID => $result->getId(),
                UrlRewrite::ENTITY_TYPE => PostUrlRewriteGenerator::ENTITY_TYPE
            ]);

            $this->urlPersist->replace($urls);
        }
    }

    /**
     * @param PostInterface $subject
     * @param PostInterface $result
     * @return void
     */
    public function afterAfterDelete(
        PostInterface $subject,
        PostInterface $result
    ) {
        if ($subject->isDeleted()) {
            $this->urlPersist->deleteByData([
                UrlRewrite::ENTITY_ID => $result->getId(),
                UrlRewrite::ENTITY_TYPE => PostUrlRewriteGenerator::ENTITY_TYPE
            ]);
        }
    }
}
