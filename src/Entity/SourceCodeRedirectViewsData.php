<?php

namespace Drupal\atge_source_code_redirect\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Source code redirect entities.
 */
class SourceCodeRedirectViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
