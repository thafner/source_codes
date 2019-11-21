<?php

namespace Drupal\demo_source_code_redirect;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Source code redirect entity.
 *
 * @see \Drupal\demo_source_code_redirect\Entity\SourceCodeRedirect.
 */
class SourceCodeRedirectAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\demo_source_code_redirect\Entity\SourceCodeRedirectInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished source code redirect entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published source code redirect entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit source code redirect entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete source code redirect entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add source code redirect entities');
  }

}
