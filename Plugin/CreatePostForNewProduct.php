<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Plugin;

use Convert\Blog\Api\PostRepositoryInterface;
use Convert\Blog\Model\PostFactory;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Controller\Adminhtml\Product\Save;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class CreatePostForNewProduct
 * @package Convert\Blog\Plugin
 */
class CreatePostForNewProduct
{
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;
    /**
     * @var PostFactory
     */
    private $postFactory;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var UrlInterface
     */
    private $urlInterface;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param RequestInterface $request
     * @param PostRepositoryInterface $postRepository
     * @param PostFactory $postFactory
     * @param ProductRepositoryInterface $productRepository
     * @param UrlInterface $urlInterface
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        RequestInterface $request,
        PostRepositoryInterface $postRepository,
        PostFactory $postFactory,
        ProductRepositoryInterface $productRepository,
        UrlInterface $urlInterface,
        StoreManagerInterface $storeManager
    )
    {
        $this->request = $request;
        $this->postRepository = $postRepository;
        $this->postFactory = $postFactory;
        $this->productRepository = $productRepository;
        $this->urlInterface = $urlInterface;
        $this->storeManager = $storeManager;
    }

    /**
     * @param Save $subject
     * @param callable $proceed
     * @return Redirect
     * @throws LocalizedException
     */
    public function aroundExecute(Save $subject, callable $proceed): Redirect
    {
        if (!$this->request->getParam('id')) {
            $productSku = $this->request->getParam('product')['sku'];
            $result = $proceed();

            if ($product = $this->getProduct($productSku)) {
                $newPost = $this->postFactory->create();
                $productUrl = $this->getProductUrl($product);

                $newPost->setIsDraft(false)
                    ->setUrlKey("new-product-{$product->getSku()}")
                    ->setTitle("New product is available in our store - {$product->getName()}")
                    ->setContent("New product is available in our store <a href=\"$productUrl\">{$product->getName()}</a> [{$product->getSku()}].");

                $this->postRepository->save($newPost);

                return $result;
            }
        }

        return $proceed();
    }

    /**
     * @param string $productSku
     * @return ProductInterface|null
     */
    private function getProduct(string $productSku): ?ProductInterface
    {
        try {
            return  $this->productRepository->get($productSku);
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * @param ProductInterface $product
     * @return string
     */
    private function getProductUrl(ProductInterface $product): string
    {
        return str_replace(
            'admin/',
            '',
            $this->urlInterface->getRouteUrl('catalog/product/view', ['id' => $product->getId()])
        );
    }
}
