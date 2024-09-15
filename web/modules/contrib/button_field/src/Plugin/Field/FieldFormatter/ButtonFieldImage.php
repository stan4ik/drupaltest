<?php

namespace Drupal\button_field\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'image button' field formatter.
 *
 * @FieldFormatter(
 *   id = "button_field_image",
 *   label = @Translation("Image Button"),
 *   description = @Translation("An image button formatter."),
 *   field_types = {
 *     "button_field"
 *   }
 * )
 */
class ButtonFieldImage extends ButtonFieldBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
      'image_path' => '',
      'alt_text' => '',
      'title_text' => '',
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   *
   * @todo Provide a file upload element.
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);
    $element['image_path'] = array(
      '#type' => 'textfield',
      '#title' =>$this->t('Image path'),
      '#default_value' => $this->getSetting('image_path'),
      '#required' => TRUE,
    );

    $element['alt_text'] = array(
      '#type' => 'textfield',
      '#title' =>$this->t('Alt text'),
      '#default_value' => $this->getSetting('alt_text') ?: $this->fieldDefinition->getLabel(),
      '#required' => TRUE,
    );

    $element['title_text'] = array(
      '#type' => 'textfield',
      '#title' =>$this->t('Title text'),
      '#default_value' => $this->getSetting('title_text'),
      '#required' => FALSE,
    );

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();
    $summary[] =$this->t('Image path: !path', array('!path' => $this->getSetting('image_path')));
    $summary[] =$this->t('Alt text: !text', array('!text' => $this->getSetting('alt_text') ?: $this->fieldDefinition->getLabel()));
    $summary[] =$this->t('Title text: !text', array('!text' => $this->getSetting('title_text')));

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  protected function elementProperties() {
    return array(
      '#type' => 'image_button',
      '#src' => $this->getSetting('image_path'),
      '#attributes' => array(
        'alt' => Html::escape(($this->getSetting('alt_text') ?: $this->fieldDefinition->getLabel())),
        'title' => Html::escape($this->getSetting('title_text')),
      ),
    );
  }

}
