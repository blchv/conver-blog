<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;

/**
 * Class PageActions
 * @package Convert\Blog\Ui\Component\Listing\Columns
 */
class PostActions extends Column
{
    /** Url path */
    const BLOG_POST_EDIT = 'blog/post/edit';
    const BLOG_POST_DELETE = 'blog/post/delete';
    /**
     * @var Escaper
     */
    private $escaper;
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Escaper $escaper
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        Escaper            $escaper,
        UrlInterface       $urlBuilder,
        array              $components = [],
        array              $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->escaper = $escaper;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $post) {
                $name = $this->getData('name');
                if (isset($post['entity_id'])) {
                    $post[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl(self::BLOG_POST_EDIT, ['entity_id' => $post['entity_id']]),
                        'label' => __('Edit'),
                        '__disableTmpl' => true,
                    ];
                    $title = $this->escaper->escapeHtml($post['title']);
                    $post[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(self::BLOG_POST_DELETE, ['entity_id' => $post['entity_id']]),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete %1', $title),
                            'message' => __('Are you sure you want to delete a %1 record?', $title),
                            '__disableTmpl' => true,
                        ],
                        'post' => true,
                        '__disableTmpl' => true,
                    ];
                }
            }
        }

        return $dataSource;
    }
}
