<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Model;

use Convert\Blog\Api\PostSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Class PostSearchResults
 * @package Convert\Blog\Model
 */
class PostSearchResults extends SearchResults implements PostSearchResultsInterface
{

}
