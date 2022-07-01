<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Test\Integration;

use Convert\Blog\Setup\Patch\Data\InitializeAuthorRole;
use Magento\Authorization\Model\ResourceModel\Role\Collection;
use Magento\Authorization\Model\Role;
use Magento\Authorization\Setup\AuthorizationFactory;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthorRoleAndAclTest
 * @package Convert\Blog\Test\Integration
 */
class AuthorRoleAndAclTest extends TestCase
{
    /**
     * @return AuthorizationFactory
     */
    private function instantiateAuthorizationFactory(): AuthorizationFactory
    {
        return ObjectManager::getInstance()
            ->create(AuthorizationFactory::class);
    }

    /**
     * @return Collection
     */
    private function prepareRoleCollection(): Collection
    {
        $roleCollection = $this->instantiateAuthorizationFactory()
            ->createRoleCollection();

        return $roleCollection->addFieldToFilter('role_name', 'Blog Author');
    }

    /**
     * @param int|null $roleId
     * @return \Magento\Authorization\Model\ResourceModel\Rules\Collection
     */
    private function prepareRulesCollection(?int $roleId = null): \Magento\Authorization\Model\ResourceModel\Rules\Collection
    {
        $ruleCollection = $this->instantiateAuthorizationFactory()
            ->createRulesCollection();

        if ($roleId || $roleId === 0) {
            $ruleCollection->addFieldToFilter('role_id', $roleId);
        }

        return $ruleCollection;
    }

    public function testAuthorRoleExists()
    {
         $resultCount = $this->prepareRoleCollection()->getSize();

        $this->assertEquals(1, $resultCount);
    }

    public function testAuthRoleHasCorrectPermissions()
    {
        ObjectManager::getInstance()
            ->create(InitializeAuthorRole::class)
            ->apply();

        $roleCollection = $this->prepareRoleCollection();
        $authorRole = null;
        foreach ($roleCollection as $role) {
            $authorRole = $role;
            break;
        }
        $this->assertInstanceOf(Role::class, $authorRole);

        $rules = $this->prepareRulesCollection($authorRole->getId())->getItems();
        $this->assertNotEmpty($rules);

        $allRules = $this->prepareRulesCollection()->getByRoles($authorRole->getId());

        $this->assertGreaterThanOrEqual(count(InitializeAuthorRole::ALLOW_RULES), $allRules->count());

        foreach ($allRules as $rule) {
            $this->assertContains($rule->getResourceId(), InitializeAuthorRole::ALLOW_RULES);
        }
    }
}
