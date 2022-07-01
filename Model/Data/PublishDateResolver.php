<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Model\Data;


/**
 * Class PublishDateResolver
 * @package Convert\Blog\Model\Data
 */
class PublishDateResolver
{
    /**
     * @param string $timestamp
     * @return void
     */
    public function resolve(string $timestamp): string
    {
        return \date('Y-m-d', \strtotime($timestamp));
    }
}
