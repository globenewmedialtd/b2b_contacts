<?php

namespace Drupal\b2b_contacts;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\group\Entity\GroupInterface;

/**
 * Defines the access control handler for the b2b_contacts entity type.
 */
class B2bContactsAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    $is_group_manager = FALSE;
    $is_owner = $entity->getOwnerId() === $account->id();
    $group = $entity->getGroup();

    if ($group instanceOf GroupInterface) {
      $is_group_manager = $group->access('update', $account);
    }

    switch ($operation) {
      case 'view':
        if ($is_owner) {
          return AccessResult::allowedIfHasPermission($account, 'view own b2b_contacts');
        }
        if ($is_group_manager) {
          return AccessResult::allowedIfHasPermission($account, 'view any b2b_contacts of my group');
        }
        return AccessResult::allowedIfHasPermission($account, 'view b2b_contacts');

      case 'update':
        if ($is_owner) {
          return AccessResult::allowedIfHasPermission($account, ['edit own b2b_contacts', 'administer b2b_contacts'], 'OR');
        }
        return AccessResult::allowedIfHasPermissions($account, ['edit b2b_contacts', 'administer b2b_contacts'], 'OR');

      case 'delete':
        if ($is_owner) {
          return AccessResult::allowedIfHasPermission($account, ['delete own b2b_contacts', 'administer b2b_contacts'], 'OR');
        }
        return AccessResult::allowedIfHasPermissions($account, ['delete b2b_contacts', 'administer b2b_contacts'], 'OR');

      default:
        // No opinion.
        return AccessResult::neutral();
    }

  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions($account, ['create b2b_contacts', 'administer b2b_contacts'], 'OR');
  }

}
