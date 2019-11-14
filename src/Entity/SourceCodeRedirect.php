<?php

namespace Drupal\atge_source_code_redirect\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\link\LinkItemInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Source code redirect entity.
 *
 * @ingroup atge_source_code_redirect
 *
 * @ContentEntityType(
 *   id = "source_code_redirect",
 *   label = @Translation("Source code redirect"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\atge_source_code_redirect\SourceCodeRedirectListBuilder",
 *     "views_data" = "Drupal\atge_source_code_redirect\Entity\SourceCodeRedirectViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\atge_source_code_redirect\Form\SourceCodeRedirectForm",
 *       "add" = "Drupal\atge_source_code_redirect\Form\SourceCodeRedirectForm",
 *       "edit" = "Drupal\atge_source_code_redirect\Form\SourceCodeRedirectForm",
 *       "delete" = "Drupal\atge_source_code_redirect\Form\SourceCodeRedirectDeleteForm",
 *     },
 *     "access" = "Drupal\atge_source_code_redirect\SourceCodeRedirectAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\atge_source_code_redirect\SourceCodeRedirectHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "source_code_redirect",
 *   admin_permission = "administer source code redirect entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "source_code",
 *     "uid" = "user_id"
 *   },
 *   links = {
 *     "canonical" = "/admin/content/atge/source-code-redirect/",
 *     "add-form" = "/admin/content/atge/source-code-redirect/add",
 *     "edit-form" = "/admin/content/atge/source-code-redirect/{source_code_redirect}/edit",
 *     "delete-form" = "/admin/content/atge/source-code-redirect/{source_code_redirect}/delete",
 *     "collection" = "/admin/content/atge/source-code-redirect/list",
 *   },
 *   field_ui_base_route = "source_code_redirect.settings"
 * )
 */
class SourceCodeRedirect extends ContentEntityBase implements SourceCodeRedirectInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
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
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceCode() {
    return $this->get('source_code')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSourceCode($source_code) {
    $this->set('source_code', $source_code);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRedirect() {
    return $this->get('redirect')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRedirect($redirect) {
    $this->set('redirect', $redirect);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['source_code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Source Code'))
      ->setDescription(t('The source code that will be attached to the redirect. Must be alpha numeric.'))
      // The cookie value can be more than alphanumeric but we limit it just to be safe.
      // @link https://stackoverflow.com/a/1969339
      ->setPropertyConstraints('value', ['Regex' => '/^[a-zA-Z0-9]*$/'])
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'text_textfield',
        'weight' => 90,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['redirect'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Redirect'))
      ->setDescription(t('The redirect this source code applies to.'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'redirect')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('form', [
        // @see \Drupal\atge_source_code_redirect\Plugin\EntityReferenceSelection\RedirectSelection
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE);

    return $fields;
  }

}
