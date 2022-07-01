<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Ui\DataProvider\Post;

use Convert\Blog\Model\PostFactory;
use Convert\Blog\Model\ResourceModel\Post\Collection;
use Convert\Blog\Model\ResourceModel\Post\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;
    /**
     * @var PostFactory
     */
    private $postFactory;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param PostFactory $postFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string                 $name,
        string                 $primaryFieldName,
        string                 $requestFieldName,
        CollectionFactory      $collectionFactory,
        PostFactory            $postFactory,
        DataPersistorInterface $dataPersistor,
        array                  $meta = [],
        array                  $data = []
    )
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->meta = $this->prepareMeta($this->meta);
        $this->postFactory = $postFactory;
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta): array
    {
        return $meta;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $posts = $this->collection->getItems();

        foreach ($posts as $post) {
            $this->loadedData[$post->getId()] = $post->getData();
        }

        $data = $this->dataPersistor->get('convert_blog_post');
        if (!empty($data)) {
            $post = $this->postFactory->create();
            $post->setData($data);
            $this->loadedData[$post->getId()] = $post->getData();
            $this->dataPersistor->clear('convert_blog_post');
        }

        return $this->loadedData;
    }
}
