<?php

namespace Drupal\button_field\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'button html' widget.
 *
 * @FieldWidget(
 *   id = "button_field_html",
 *   label = @Translation("HTML Button"),
 *   description = @Translation("An HTML button widget."),
 *   field_types = {
 *     "button_field"
 *   }
 * )
 */
class ButtonFieldHtml extends ButtonFieldBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
      'text' => '',
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);
    $element['text'] = array(
      '#type' => 'textfield',
      '#title' =>$this->t('Button Text'),
      '#default_value' => $this->getSetting('text') ?: $this->fieldDefinition->getLabel(),
      '#required' => TRUE,
    );

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();
    $summary[] = $this->t('Button title: @text', [
      '@text' => $this->getSetting('text') ?: $this->fieldDefinition->getLabel(),
    ]);

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  protected function elementProperties() {
    return array(
      '#type' => 'button',
      '#value' => Html::escape($this->getSetting('text') ?: $this->fieldDefinition->getLabel()),
    );
  }

}
