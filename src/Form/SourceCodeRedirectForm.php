<?php

namespace Drupal\atge_source_code_redirect\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Source code redirect edit forms.
 *
 * @ingroup atge_source_code_redirect
 */
class SourceCodeRedirectForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\atge_source_code_redirect\Entity\SourceCodeRedirect */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Source code redirect.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Source code redirect.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.source_code_redirect.canonical');
  }

}
