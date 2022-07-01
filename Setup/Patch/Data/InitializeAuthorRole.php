<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Setup\Patch\Data;

use Exception;
use Magento\Authorization\Model\Acl\Loader\RuleFactory;
use Magento\Authorization\Model\Role;
use Magento\Authorization\Model\Rules;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Authorization\Setup\AuthorizationFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use \Magento\Authorization\Model\Acl\Role\Group as RoleGroup;
use Magento\Authorization\Model\ResourceModel\Role as RoleResourceModel;
use Magento\Authorization\Model\ResourceModel\Rules as RulesResourceModel;
use \Magento\Framework\Setup\Patch\PatchRevertableInterface;

/**
 * Class InitializeAuthorRole
 * @package Convert\Blog\Setup\Patch\Data
 */
class InitializeAuthorRole implements DataPatchInterface, PatchRevertableInterface, PatchVersionInterface
{
    const ALLOW_RULES = [
        'Magento_Backend::admin',
        'Magento_Backend::content',
        'Magento_Backend::content_elements',
        'Convert_Blog::post',
        'Convert_Blog::post_list',
        'Convert_Blog::post_save',
        'Convert_Blog::post_delete'
    ];

    /**
     * @var AuthorizationFactory
     */
    private $authorizationFactory;
    /**
     * @var RoleResourceModel
     */
    private $roleResourceModel;
    /**
     * @var RuleFactory
     */
    private $ruleFactory;
    /**
     * @var RulesResourceModel
     */
    private $rulesResourceModel;

    /**
     * @param AuthorizationFactory $authorizationFactory
     * @param RoleResourceModel $roleResourceModel
     * @param RulesResourceModel $rulesResourceModel
     * @param RuleFactory $ruleFactory
     */
    public function __construct(
        AuthorizationFactory $authorizationFactory,
        RoleResourceModel    $roleResourceModel,
        RulesResourceModel   $rulesResourceModel,
        RuleFactory          $ruleFactory
    )
    {
        $this->authorizationFactory = $authorizationFactory;
        $this->roleResourceModel = $roleResourceModel;
        $this->ruleFactory = $ruleFactory;
        $this->rulesResourceModel = $rulesResourceModel;
    }

    /**
     * @return array|string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public static function getVersion(): string
    {
        return '0.0.1';
    }

    /**
     * @throws AlreadyExistsException
     */
    public function apply()
    {
        $this->processRules($this->prepareBlogAuthorRole());
    }

    /**
     * @param Role $blogAuthorRole
     * @return void
     * @throws AlreadyExistsException
     */
    private function processRules(Role $blogAuthorRole)
    {
        $ruleCollection = $this->authorizationFactory->createRulesCollection()
            ->addFieldToFilter('role_id', $blogAuthorRole->getId());

        if ($ruleCollection->getSize() === 0) {
            foreach (self::ALLOW_RULES as $resourceId) {
                $this->rulesResourceModel->save(
                    $this->authorizationFactory->createRules()
                        ->setData([
                            'role_id' => $blogAuthorRole->getId(),
                            'resource_id' => $resourceId,
                            'privileges' => null,
                            'permission' => 'allow'
                        ])
                );
            }
        }
    }

    /**
     * @return Role
     * @throws AlreadyExistsException
     */
    private function prepareBlogAuthorRole(): Role
    {
        $roleCollection = $this->authorizationFactory->createRoleCollection()
            ->addFieldToFilter('role_name', 'Blog Author');

        if ($roleCollection->getSize() === 0) {
            $blogAuthorRole = $this->authorizationFactory->createRole()
                ->setData([
                    'parent_id' => 0,
                    'tree_level' => 1,
                    'sort_order' => 1,
                    'role_type' => RoleGroup::ROLE_TYPE,
                    'user_id' => 0,
                    'user_type' => UserContextInterface::USER_TYPE_ADMIN,
                    'role_name' => 'Blog Author',
                ]);
            $this->roleResourceModel->save($blogAuthorRole);
        } else {
            foreach ($roleCollection as $role) {
                $blogAuthorRole = $role;
                break;
            }
        }

        return $blogAuthorRole;
    }

    /**
     * @return array|string[]
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @return void
     * @throws Exception
     */
    public function revert()
    {
        $roleCollection = $this->authorizationFactory->createRoleCollection()
            ->addFieldToFilter('role_name', 'Blog Author');

        switch ($roleCollection->getSize()) {
            case 0:
                echo "Error: No \"Blog Author\" role found. \n";
                break;
            case 1:
                foreach($roleCollection as $role) {
                    echo "Deleting \"{$role->getData('role_name')}\" role... ";
                    $this->roleResourceModel->delete($role);
                    echo "Done \n";
                }
                break;
            default:
                echo "Error: Multiple roles with name \"Blog Author\" were detected. Cannot determine which one to remove.";
                break;
        }

    }
}
