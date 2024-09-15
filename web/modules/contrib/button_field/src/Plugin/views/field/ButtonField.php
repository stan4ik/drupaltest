<?php

namespace Drupal\button_field\Plugin\views\field;

use Drupal\views\Plugin\views\field\EntityField;
use Drupal\views\ResultRow;

/**
 * Provides a field handler for button_field fields.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("button_field")
 */
class ButtonField extends EntityField {

  /**
   * {@inheritdoc}
   */
  public $multiple = FALSE;

  /**
   * {@inheritdoc}
   */
  public function getItems(ResultRow $values) {
    $display = [
      'type' => $this->options['type'],
      'settings' => $this->options['settings'],
      'label' => 'hidden',
    ];
    // Optional relationships may not provide an entity at all. So we can't
    // use createEntityForGroupBy() for those rows.
    if ($entity = $this->getEntity($values)) {
      $entity = $this->createEntityForGroupBy($entity, $values);
      // Some bundles might not have a specific field, in which case the faked
      // entity doesn't have it either.
      $build_list = isset($entity->{$this->definition['field_name']}) ? $entity->{$this->definition['field_name']}->view($display) : NULL;
    }
    else {
      $build_list = NULL;
    }

    if (!$build_list) {
      return [];
    }

    return [['rendered' => $build_list]];
  }

  /**
   * {@inheritdoc}
   */
  public function usesGroupBy() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function canExpose() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isValueEmpty($value, $empty_zero, $no_skip_empty = TRUE) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function clickSortable() {
    return FALSE;
  }

}
