<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);
namespace Convert\Blog\Ui\Component\Listing\Columns;

use Convert\Blog\Model\Data\AuthorResolver;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Author
 * @package Convert\Blog\Ui\Component\Listing\Columns
 */
class Author extends Column
{
    /**
     * @var \Convert\Blog\Model\Data\AuthorResolver
     */
    private $authorResolver;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        AuthorResolver $authorResolver,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->authorResolver = $authorResolver;
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $post) {
                $post['author'] = $this->authorResolver->resolve($post['author']);
            }
        }

        return $dataSource;
    }
}
