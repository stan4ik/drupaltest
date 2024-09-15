<?php

namespace Drupal\button_field\Plugin\Field\FieldFormatter;

use Drupal\button_field\ButtonFieldAjaxTrait;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Base implementation for button field formatters.
 */
abstract class ButtonFieldBase extends FormatterBase {

  use ButtonFieldAjaxTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    // Get the entity that we're rendering the field for.
    $entity = $items->getEntity();
    $entity_type = $entity->getEntityType()->id();
    $entity_id = $entity->id();

    // If any additional classes have been defined then add them to the classes
    // now.
    $class = array('button_field');
    $additional_classes = $this->getFieldSetting('additional_classes');
    if (!empty($additional_classes)) {
      $class = array_merge($class, explode(' ', $additional_classes));
    }

    // Button elements do not obey the #description index, so if a description
    // has been set we need to build our own suffix.
    $suffix = NULL;
    $description = $this->fieldDefinition->getDescription();
    if (!empty($description)) {
      $suffix = '<div class="description">' . $description . '</div>';
    }

    $id = $this->elementId(0, $items->getLangcode());
    $element = array(
      '#id' => $id,
      '#name' => $id,
      '#attributes' => array('class' => $class),
      '#description' =>$this->t('this is the help text'),
      '#suffix' => $suffix,
      '#entity_type' => $entity_type,
      '#entity' => $entity,
      '#field_name' => $items->getName(),
      '#limit_validation_errors' => [],
      '#attached' => array(
        'drupalSettings' => array(
          'button_field' => array(
            $id => array(
              'entity_type'=> $entity_type,
              'entity_id' => $entity_id,
              'confirmation' => $this->getFieldSetting('confirmation'),
            ),
          ),
        ),
      ),
      '#ajax' => array(
        'callback' => [self::class, 'handleClick'],
      ),
    );

    $element += $this->elementProperties();
    return \Drupal::formBuilder()->getForm(
      'Drupal\button_field\Form\DummyForm',
      $element
    );
  }

  /**
   * Retrieves the properties for the current widget's element.
   *
   * @return array
   *   Properties for the widget element.
   */
  abstract protected function elementProperties();

  /**
   * Builds the id for a button field element.
   *
   * @todo Determine if there is a better way to do this.
   */
  protected function elementId($delta, $language) {
    $parts = array(
      'view',
      str_replace('_', '-', $this->fieldDefinition->getName()),
      $language,
      $delta,
      'value',
    );

    return implode('-', $parts);
  }

}
