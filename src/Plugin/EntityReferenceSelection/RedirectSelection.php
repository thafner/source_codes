<?php

namespace Drupal\atge_source_code_redirect\Plugin\EntityReferenceSelection;

use Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection;

/**
 * Provides autocomplete matching for redirect entity.
 *
 * The Redirect entity's key for the "label" field is not an actual field in
 * the database, so the DefaultSelection will fail.
 *
 * @see \Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection::buildEntityQuery()
 * @see \Drupal\user\Plugin\EntityReferenceSelection\UserSelection
 *
 * @EntityReferenceSelection(
 *   id = "default:redirect",
 *   label = @Translation("Redirect selection"),
 *   entity_types = {"redirect"},
 *   group = "default",
 *   weight = 1
 * )
 */
class RedirectSelection extends DefaultSelection {

  /**
   * {@inheritdoc}
   */
  protected function buildEntityQuery($match = NULL, $match_operator = 'CONTAINS') {
    $configuration = $this->getConfiguration();
    $target_type = $configuration['target_type'];
    $entity_type = $this->entityTypeManager->getDefinition($target_type);

    $query = $this->entityTypeManager->getStorage($target_type)->getQuery();

    // If 'target_bundles' is NULL, all bundles are referenceable, no further
    // conditions are needed.
    if (is_array($configuration['target_bundles'])) {
      // If 'target_bundles' is an empty array, no bundle is referenceable,
      // force the query to never return anything and bail out early.
      if ($configuration['target_bundles'] === []) {
        $query->condition($entity_type->getKey('id'), NULL, '=');
        return $query;
      }
      else {
        $query->condition($entity_type->getKey('bundle'), $configuration['target_bundles'], 'IN');
      }
    }

    if (isset($match)) {
      // IMPORTANT!!! This is the only line in this method different from parent::buildEntityQuery().
      // @todo Make this more fututre proof by simply modifying the query the parent method returns.
      $query->condition('redirect_source__path', $match, $match_operator);
    }

    // Add entity-access tag.
    $query->addTag($target_type . '_access');

    // Add the Selection handler for system_query_entity_reference_alter().
    $query->addTag('entity_reference');
    $query->addMetaData('entity_reference_selection_handler', $this);

    // Add the sort option.
    if ($configuration['sort']['field'] !== '_none') {
      $query->sort($configuration['sort']['field'], $configuration['sort']['direction']);
    }

    return $query;
  }

}
