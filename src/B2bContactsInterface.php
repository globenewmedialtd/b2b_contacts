<?php

namespace Drupal\b2b_contacts;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a b2b_contacts entity type.
 */
interface B2bContactsInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the b2b_contacts company.
   *
   * @return string
   *   Company of the b2b_contacts.
   */
  public function getCompany();

  /**
   * Sets the b2b_contacts company.
   *
   * @param string $company
   *   The b2b_contacts company.
   *
   * @return \Drupal\b2b_contacts\B2bContactsInterface
   *   The called b2b_contacts entity.
   */
  public function setCompany($company);

  /**
   * Gets the b2b_contacts creation timestamp.
   *
   * @return int
   *   Creation timestamp of the b2b_contacts.
   */
  public function getCreatedTime();

  /**
   * Sets the b2b_contacts creation timestamp.
   *
   * @param int $timestamp
   *   The b2b_contacts creation timestamp.
   *
   * @return \Drupal\b2b_contacts\B2bContactsInterface
   *   The called b2b_contacts entity.
   */
  public function setCreatedTime($timestamp);

}
