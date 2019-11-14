<?php

namespace Drupal\atge_source_code_redirect\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Source code redirect entities.
 *
 * @ingroup atge_source_code_redirect
 */
interface SourceCodeRedirectInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Returns the Source code redirect source code value.
   *
   * @return string
   *   Source code redirect Source Code value to be associated with the redirect
   */
  public function getSourceCode();

  /**
   * Sets the Source code redirect source code value.
   *
   * @param string $source_code
   *   The Source code redirect source code value.
   *
   * @return \Drupal\atge_source_code_redirect\Entity\SourceCodeRedirectInterface
   *   The called Source code redirect entity.
   */
  public function setSourceCode($source_code);

  /**
   * Returns the Redirect associated with the source code.
   *
   * @return \Drupal\redirect\Entity\Redirect
   *   The Redirect associated.
   */
  public function getRedirect();

  /**
   * Sets the Redirect associated with the source code.
   *
   * @param \Drupal\redirect\Entity\Redirect $redirect
   *   The Redirect to associate with the source code.
   *
   * @return \Drupal\atge_source_code_redirect\Entity\SourceCodeRedirectInterface
   *   The called Source code redirect entity.
   */
  public function setRedirect($redirect);

}
