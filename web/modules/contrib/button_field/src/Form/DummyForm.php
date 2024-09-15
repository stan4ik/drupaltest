<?php

namespace Drupal\button_field\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form builder callback for the dummy form.
 *
 * This is used to render button field's on a display that is not editable.
 */
class DummyForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'button_field_dummy_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $element = $form_state->getBuildInfo()['args'][0];
    $langcode = $element['#entity']->language()->getId();

    $form[$element['#field_name']][$langcode] = $element;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild();
  }

}
