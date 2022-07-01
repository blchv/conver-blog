<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Model\Config;


use Magento\Config\Model\Config\Source\Enabledisable;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class ConfigProvider
 * @package Convert\Blog\Model
 */
class Provider
{
    const ENABLED = 'blog/general/enabled';
    const AUTHOR = 'blog/general/author';
    const SORTING = 'blog/posts/sortby';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function provideIsEnabled(): bool
    {
        return (int)$this->getConfig(self::ENABLED) === Enabledisable::ENABLE_VALUE;
    }

    /**
     * @param string $path
     * @return mixed
     */
    private function getConfig(string $path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function provideDefaultAuthor(): string
    {
        return $this->getConfig(self::AUTHOR);
    }

    /**
     * @return string
     */
    public function provideDefaultSorting(): string
    {
        return $this->getConfig(self::SORTING);
    }
}
