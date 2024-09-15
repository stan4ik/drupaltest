<?php

namespace Drupal\button_field\Plugin\migrate\field\d7;

use Drupal\migrate_drupal\Plugin\migrate\field\FieldPluginBase;

/**
 * Migrates button_field.
 *
 * @MigrateField(
 *   id = "d7_button_field",
 *   core = {7},
 *   type_map = { "button_field" = "button_field" },
 *   source_module = "button_field",
 *   destination_module = "button_field"
 * )
 */
class ButtonField extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getFieldFormatterMap() {
    return [
      'button_field_image' => 'button_field_image',
      'button_field_html' => 'button_field_html',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldWidgetMap() {
    return [
      'button_field_image' => 'button_field_image',
      'button_field_html' => 'button_field_html',
    ];
  }

}

