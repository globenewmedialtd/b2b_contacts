<?php

namespace Drupal\b2b_contacts\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\b2b_contacts\B2bContactsInterface;
use Drupal\user\UserInterface;
use Drupal\group\Entity\GroupInterface;

/**
 * Defines the b2b_contacts entity class.
 *
 * @ContentEntityType(
 *   id = "b2b_contacts",
 *   label = @Translation("B2B Contacts"),
 *   label_collection = @Translation("b2b_contacts"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\b2b_contacts\B2bContactsListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\b2b_contacts\B2bContactsAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\b2b_contacts\Form\B2bContactsForm",
 *       "edit" = "Drupal\b2b_contacts\Form\B2bContactsForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "b2b_contacts",
 *   admin_permission = "administer b2b_contacts",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "company",
 *     "gid" = "gid",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/b2b-contacts/add",
 *     "canonical" = "/b2b_contacts/{b2b_contacts}",
 *     "edit-form" = "/admin/content/b2b-contacts/{b2b_contacts}/edit",
 *     "delete-form" = "/admin/content/b2b-contacts/{b2b_contacts}/delete",
 *     "collection" = "/admin/content/b2b-contacts"
 *   },
 *   field_ui_base_route = "entity.b2b_contacts.settings"
 * )
 */
class B2bContacts extends ContentEntityBase implements B2bContactsInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   *
   * When a new b2b_contacts entity is created, set the uid entity reference to
   * the current user as the creator of the entity.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += ['uid' => \Drupal::currentUser()->id()];

    if($group =_b2b_contacts_check_group()) {
      $values += ['group' => $group];
    }

  }

  /**
   * {@inheritdoc}
   */
  public function getCompany() {
    return $this->get('company')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCompany($company) {
    $this->set('company', $company);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroup() {
    return $this->get('gid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupId() {
    return $this->get('gid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setGroupId($group_id) {
    $this->set('gid', $group_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setGroup(GroupInterface $group) {
    $this->set('group', $group->id());
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['company'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Company'))
      ->setDescription(t('The company of the B2B contacts entity.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Author'))
      ->setDescription(t('The user ID of the b2b_contacts author.'))
      ->setSetting('target_type', 'user')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['gid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Group'))
      ->setDescription(t('The group ID of the B2B contacts entity.'))
      ->setSetting('target_type', 'group')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the b2b_contacts was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the b2b_contacts was last edited.'));

    return $fields;
  }

}
