<?php

/**
 * @file
 * Contains source_code_redirect.page.inc.
 *
 * Page callback for Source code redirect entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Source code redirect templates.
 *
 * Default template: source_code_redirect.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_source_code_redirect(array &$variables) {
  // Fetch SourceCodeRedirect Entity Object.
  $source_code_redirect = $variables['elements']['#source_code_redirect'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
