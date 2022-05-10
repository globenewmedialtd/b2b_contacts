<?php

namespace Drupal\b2b_contacts\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\group\Entity\GroupInterface;
use Symfony\Component\Routing\Route;
use Drupal\Core\Routing\RouteMatch;
use Drupal\views\Views;

/**
 * Determines access to for B2B
 */
class B2bContactsPageAccessCheck implements AccessInterface {

  /**
   * Checks access to the event series view
   */
  public function access(Route $route, RouteMatch $route_match) {

    $parameters = $route_match->getParameters();
    $user = $parameters->get('user');

    // In case we are on a user view
    if (isset($user) && !is_object($user)) {
      $user = \Drupal::entityTypeManager()->getStorage('user')->load($user);
    }

    if ($group_id = _b2b_contacts_check_group()) {
      return AccessResult::allowed();
    }

    return AccessResult::forbidden();

  }

}
