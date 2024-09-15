<?php

namespace Drupal\button_field\Plugin\Condition;

use Drupal\field\Entity\FieldConfig;
use Drupal\rules\Core\RulesConditionBase;

/**
 * Provides a condition to compare fields by field name.
 *
 * @Condition(
 *   id = "button_field_compare_button",
 *   label = @Translation("Compare clicked button"),
 *   category = @Translation("Button Field"),
 *   context_definitions = {
 *     "clicked_field" = @ContextDefinition("entity:field_config",
 *       label = @Translation("Clicked field"),
 *       description = @Translation("The button field that was clicked."),
 *     ),
 *     "comparison_field" = @ContextDefinition("string",
 *       label = @Translation("Comparison field name"),
 *       description = @Translation("The field to compare the clicked button to."),
 *       options_provider = "\Drupal\button_field\TypedData\Options\ButtonFieldListOptions",
 *     )
 *   }
 * )
 */
class ButtonFieldCompareButton extends RulesConditionBase {

  /**
   * Checks if a given field configuration matches the field name.
   *
   * @param \Drupal\field\Entity\FieldConfig $field
   *   The field definition.
   * @param string $field_name
   *   The field name.
   *
   * @return bool
   *   TRUE if the field name matches.
   */
  protected function doEvaluate(FieldConfig $field, $field_name) {
    return $field->getName() === $field_name;
  }

}
