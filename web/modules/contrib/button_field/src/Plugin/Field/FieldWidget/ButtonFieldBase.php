<?php

namespace Drupal\button_field\Plugin\Field\FieldWidget;

use Drupal\button_field\ButtonFieldAjaxTrait;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Base implementation for button field widgets.
 */
abstract class ButtonFieldBase extends WidgetBase {

  use ButtonFieldAjaxTrait;

  /**
   * {@inheritdoc}
   *
   * @todo Add ajax callback.
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // Get the entity that we're rendering the field for.
    $entity = $items->getEntity();
    $entity_id = $entity->id();
    $entity_type = $entity->getEntityType();

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

    $id = $this->elementId($delta);
    $element = array(
      '#id' => $id,
      '#name' => $id,
      '#attributes' => array('class' => $class),
      '#description' =>$this->t('this is the help text'),
      '#suffix' => $suffix,
      '#entity_type' => $entity_type,
      '#entity' => $entity,
      '#field_name' => $this->fieldDefinition->getName(),
      '#required' => FALSE,
      '#limit_validation_errors' => [],
      '#attached' => array(
        'drupalSettings' => array(
          'button_field' => array(
            $id => array(
              'entity_type'=> $entity_type,
              'entity_id' => $entity_id,
              'confirmation' => $this->fieldDefinition->getSetting('confirmation'),
            ),
          ),
        ),
      ),
      '#ajax' => array(
        'callback' => [self::class, 'handleClick'],
      ),
    );

    return $this->elementProperties() + $element;
  }

  /**
   * {@inheritdoc}
   */
  public function extractFormValues(FieldItemListInterface $items, array $form, FormStateInterface $form_state) {
    // Button field does not use form values.
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
  protected function elementId($delta) {
    $parts = array(
      'edit',
      str_replace('_', '-', $this->fieldDefinition->getName()),
      $delta,
      'value',
    );

    return implode('-', $parts);
  }

}
