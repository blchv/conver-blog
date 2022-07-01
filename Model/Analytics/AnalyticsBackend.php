<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);
namespace Convert\Blog\Model\Analytics;


use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Filesystem\Directory\WriteFactory;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class AnalyticsBackend
 * @package Convert\Blog\Model\Analytics
 */
class AnalyticsBackend
{
    const ANALYTICS_DIR = 'post_analytics';

    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var WriteFactory
     */
    private $writeFactory;
    /**
     * @var File
     */
    private $io;
    /**
     * @var DirectoryList
     */
    private $directoryList;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param Filesystem $filesystem
     * @param WriteFactory $writeFactory
     * @param File $io
     * @param DirectoryList $directoryList
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Filesystem $filesystem,
        WriteFactory $writeFactory,
        File $io,
        DirectoryList $directoryList,
        SerializerInterface $serializer
    ) {
        $this->filesystem = $filesystem;
        $this->writeFactory = $writeFactory;
        $this->io = $io;
        $this->directoryList = $directoryList;
        $this->serializer = $serializer;
    }

    /**
     * @return Filesystem\Directory\Write
     */
    private function initWrite(): Filesystem\Directory\Write
    {
        $varDir = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
        return $this->writeFactory->create($varDir->getAbsolutePath(self::ANALYTICS_DIR));
    }

    /**
     * @return ReadInterface
     */
    private function getDirectory(): ReadInterface
    {
        try {
            $result = $this->initWrite();
        } catch (FileSystemException $e) {
            $result = $this->createAnalyticsDirectory();
        }

        return $result;
    }

    private function createAnalyticsDirectory()
    {
        $ioResult = $this->io->mkdir($this->directoryList->getPath(
            DirectoryList::VAR_DIR). '/' . self::ANALYTICS_DIR,
            0755
        );

        return $this->initWrite();
    }

    /**
     * @param int $postId
     * @return bool
     * @throws FileSystemException
     */
    public function incrementViewCount(int $postId): bool
    {
        $analyticsDir = $this->getDirectory();
        $postData = $this->getPostData($postId, $analyticsDir);
        $postData['view_count'] = ++$postData['view_count'];
        $persistData = $this->persistPostData($postId, $postData, $analyticsDir);

        return (bool)$persistData;
    }

    /**
     * @param int $postId
     * @return int|null
     * @throws FileSystemException
     */
    public function parseViewCount(int $postId): ?int
    {
        $analyticsDir = $this->getDirectory();
        $postData = $this->getPostData($postId, $analyticsDir);

        return $postData['view_count'];
    }

    /**
     * @param int $postId
     * @param ReadInterface $analyticsDir
     * @return array|bool|float|int|string|null
     * @throws FileSystemException
     */
    private function getPostData(int $postId, ReadInterface $analyticsDir)
    {
        if (!$analyticsDir->isExist("post_$postId")) {
            $analyticsDir->writeFile("post_$postId", $this->getInitPostAnalyticsContent($postId));
        }

        $fileContents = $analyticsDir->readFile("post_$postId");

        return $this->serializer->unserialize($fileContents);
    }

    /**
     * @param int $postId
     * @return string
     */
    private function getInitPostAnalyticsContent(int $postId): string
    {
        return $this->serializer->serialize([
            'post_id' => $postId,
            'view_count' => 0
        ]);
    }

    /**
     * @param int $postId
     * @param array $postData
     * @param ReadInterface $analyticsDir
     * @return mixed
     */
    private function persistPostData(int $postId, array $postData, ReadInterface $analyticsDir)
    {
        return $analyticsDir->writeFile(
            "post_$postId",
            $this->serializer->serialize($postData)
        );
    }
}
