<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Controller\Post;

use Convert\Blog\Model\Config\Provider;
use Convert\Blog\Model\ResourceModel\Post\CollectionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Index
 * @package Convert\Blog\Controller\Index
 */
class View extends Action implements HttpGetActionInterface
{
    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @var Provider
     */
    private $configProvider;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param Context $context
     * @param ResultFactory $resultFactory
     * @param Provider $configProvider
     */
    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        Provider $configProvider,
        RequestInterface $request,
        CollectionFactory $collectionFactory
    )
    {
        parent::__construct($context);
        $this->resultFactory = $resultFactory;
        $this->configProvider = $configProvider;
        $this->request = $request;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        if ($this->configProvider->provideIsEnabled() && $this->postExists()) {
            /** @var \Magento\Framework\View\Result\Page $resultPage */
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            return $resultPage;
        }

        $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        return $resultForward->forward('noroute');
    }

    /**
     * @return bool
     */
    private function postExists(): bool
    {
        $postId = $this->request->getParam('entity_id');
        $postCount = $this->collectionFactory
            ->create()
            ->addFilter('entity_id', $postId)
            ->addFilter('is_draft', false)
            ->getSize();

        return $postCount === 1;
    }
}
