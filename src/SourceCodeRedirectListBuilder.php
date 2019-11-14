<?php

namespace Drupal\atge_source_code_redirect;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Source code redirect entities.
 *
 * @ingroup atge_source_code_redirect
 */
class SourceCodeRedirectListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['source_code'] = $this->t('Source Code');
    $header['redirect'] = $this->t('Redirect');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['source_code'] = $entity->source_code->value;
    $row['redirect'] = $entity->redirect->entity->label();

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function getOperations(EntityInterface $entity) {
    $operations = parent::getOperations($entity);

    $redirect = $entity->get('redirect')->entity;
    if (!empty($redirect)) {
      $operations['edit-redirect'] = [
        'title' => t('Edit Redirect'),
        'url' => $redirect->toUrl('edit-form'),
      ];
    }

    return $operations;
  }

}
