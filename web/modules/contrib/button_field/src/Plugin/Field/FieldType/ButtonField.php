<?php

namespace Drupal\button_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'button_field' field type.
 *
 * @FieldType(
 *   id = "button_field",
 *   label = @Translation("Button"),
 *   description = @Translation("Displays a button that, when clicked, fires a rules event."),
 *   default_widget = "button_field_html",
 *   default_formatter = "button_field_html",
 *   cardinality = 1,
 * )
 */
class ButtonField extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    $settings = parent::defaultStorageSettings();
    $settings['confirmation'] = '';

    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    $settings = parent::defaultFieldSettings();
    $settings['additional_classes'] = '';

    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return array(
      'columns' => array(
        'value' => array(
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Value'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $element = array();

    $element['additional_classes'] = array(
      '#type' => 'textfield',
      '#title' =>$this->t('Additional classes'),
      '#default_value' => $this->getSetting('additional_classes'),
      '#description' =>$this->t('Optionally, specify any classes to be applied to the element. All button field elements will always have the "button_field" class. Separate multiple classes with a space.'),
    );

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element = array();

    $element['confirmation'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Confirmation message'),
      '#default_value' => $this->getSetting('confirmation'),
      '#description' => $this->t('You may enter a confirmation message to be displayed to the user before running any rules. If you do not want the user to see a confirmation message you can leave this setting empty.'),
    );

    return $element;
  }

}
