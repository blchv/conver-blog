<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Controller\Index;

use Convert\Blog\Model\Config\Provider;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Index
 * @package Convert\Blog\Controller\Index
 */
class Index extends Action implements HttpGetActionInterface
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
     * @param Context $context
     * @param ResultFactory $resultFactory
     * @param Provider $configProvider
     */
    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        Provider $configProvider
    )
    {
        parent::__construct($context);
        $this->resultFactory = $resultFactory;
        $this->configProvider = $configProvider;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        if ($this->configProvider->provideIsEnabled()) {
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $resultPage->getConfig()->getTitle()->set(__('Blog'));
            return $resultPage;
        }

        $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        return $resultForward->forward('noroute');
    }
}
