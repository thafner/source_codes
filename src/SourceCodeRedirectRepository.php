<?php

namespace Drupal\atge_source_code_redirect;

use Drupal\Core\Database\Connection;
use Drupal\redirect\Entity\Redirect;

class SourceCodeRedirectRepository {
  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * SourceCodeRedirectRepository constructor.
   * @param \Drupal\Core\Database\Connection $connection
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * Match a url to an source code redirect path
   *
   * @param \Drupal\redirect\Entity\Redirect $redirect
   *   The redirect to find a source code for.
   *
   * @return \Drupal\atge_source_code_redirect\Entity\SourceCodeRedirect
   *   The source code entity for the redirect.
   */
  public function findMatchingSourceCodeRedirect(Redirect $redirect) {
    $source_code = NULL;

    // Bypass entity query for better performance.
    $conn = $this->connection;
    $source_values = $conn->query("SELECT source_code FROM {source_code_redirect} WHERE redirect = :from", [
      ':from' => $redirect->id(),
    ])->fetchAll();

    if (!empty($source_values)) {
      if (count($source_values) > 1) {
        // @TODO log an alert level error if there are multiple
      }
      $source_code = $source_values[0]->source_code;
    }

    return $source_code;
  }

}
