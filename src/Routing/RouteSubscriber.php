<?php

namespace Drupal\b2b_contacts\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('view.b2b_contacts.page_b2b_contacts')) {
      $requirements = $route->getRequirements();
      $requirements['_social_b2b_contacts_page_custom_access'] = '\Drupal\b2b_contacts\Access\B2BContactsPageAccessCheck';
      $route->setRequirements($requirements);
    }
  }
}
