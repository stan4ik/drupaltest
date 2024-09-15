<?php

namespace Drupal\Tests\button_field\TestSite;

use Drupal\Component\Utility\Random;
use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\rules\Context\ContextConfig;
use Drupal\rules\Entity\ReactionRuleConfig;
use Drupal\TestSite\TestSetupInterface;

/**
 * Setup file used for button_field Nightwatch tests.
 */
class ButtonFieldInstallTestScript implements TestSetupInterface {

  /**
   * Modules that should be enabled.
   *
   * @var string[]
   */
  protected $modules = [
    'block',
    'dblog',
    'node',
    'field',
    'field_ui',
    'typed_data',
    'rules',
    'rules_test',
    'button_field',
  ];

  /**
   * @var \Drupal\Component\Utility\Random
   */
  protected $randomGenerator;

  /**
   * {@inheritdoc}
   */
  public function setup() {
    \Drupal::service('module_installer')->install($this->modules);

    // Turn on debug logging.
    \Drupal::configFactory()->getEditable('rules.settings')
      ->set('debug_log.enabled', TRUE)
      ->set('debug_log.system_debug', TRUE)
      ->set('system_log.log_level', 'debug')
      ->save();

    try {
      $type = $this->createContentType();
      $this->createButtonFieldStorage($type);
      $this->createRule();

      Node::create([
        'type' => $type,
        'title' => 'Test node',
      ])->save();
    }
    catch (EntityStorageException $e) {
      \Drupal::logger('button_field')->error($e->getMessage());
    }

    // Flush caches.
    \Drupal::cache()->deleteAll();
  }

  /**
   * Install configuration for modules.
   *
   * @param array $values
   *   Optional values to set on the content type.
   *
   * @return string
   *   The node type identifier.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function createContentType(array $values = []) {
    $id = !isset($values['type']) ? $this->randomMachineName() : $values['type'];
    $values += [
      'type' => $id,
      'name' => $id,
    ];

    $type = NodeType::create($values);
    $type->save();

    // Create Entity and View displays.
    $formDisplay = EntityFormDisplay::create([
      'targetEntityType' => 'node',
      'bundle' => $id,
      'mode' => 'default',
      'status' => TRUE,
    ]);
    $formDisplay->save();

    $viewDisplay = EntityViewDisplay::create([
      'targetEntityType' => 'node',
      'bundle' => $id,
      'mode' => 'default',
      'status' => TRUE,
    ]);
    $viewDisplay->save();

    return $id;
  }

  /**
   * Creates button field on node type.
   *
   * @param string $bundle
   *   The bundle of the node type to create the field config.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function createButtonFieldStorage($bundle) {
    $storage_values = [
      'field_name' => 'field_button_field',
      'entity_type' => 'node',
      'type' => 'button_field',
      'cardinality' => 1,
      'settings' => [],
    ];

    $field_storage = FieldStorageConfig::create($storage_values);
    $field_storage->save();

    $field_values = [
      'field_name' => 'field_button_field',
      'entity_type' => 'node',
      'bundle' => $bundle,
      'label' => $this->getRandomGenerator()->name(15),
      'required' => 0,
      'default_value' => [],
    ];

    $field_config = FieldConfig::create($field_values);
    $field_config->save();

    $display_id = 'node.' . $bundle . '.default';
    $formDisplay = EntityViewDisplay::load($display_id);
    $formDisplay->setComponent($field_storage->getName(), [
      'type' => 'button_field_html',
      'weight' => -1,
      'settings' => ['text' => ''],
    ]);
    $formDisplay->save();

    $viewDisplay = EntityFormDisplay::load($display_id);
    $viewDisplay->setComponent($field_storage->getName(), [
      'type' => 'button_field_html',
      'weight' => -1,
      'settings' => ['text' => ''],
    ]);
    $viewDisplay->save();
  }

  /**
   * Creates a reaction rule based on the field.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function createRule() {
    $expressionManager = \Drupal::service('plugin.manager.rules_expression');

    $expression = $expressionManager->createRule();
    $contextConfig = ContextConfig::create()
      ->map('message', 'Button field clicked!');
    $expression->addAction('rules_system_message', $contextConfig);

    $rule = ReactionRuleConfig::create([
      'id' => 'button_field_test_rule',
      'label' => 'Button Field Test Rule',
      'description' => '',
      'events' => [
        ['event_name' => 'button_field_clicked'],
      ],
      'expression' => ['id' => 'rules_rule'],
    ]);
    $rule->setExpression($expression);
    $rule->save();

    return $rule;
  }

  /**
   * Generates a unique random string containing letters and numbers.
   *
   * @param int $length
   *   Length of random string to generate.
   *
   * @return string
   *   Randomly generated unique string.
   *
   * @see \Drupal\Component\Utility\Random::name()
   */
  public function randomMachineName($length = 8): string {
    return $this->getRandomGenerator()->name($length, TRUE);
  }

  /**
   * Gets the random generator for the utility methods.
   *
   * @return \Drupal\Component\Utility\Random
   *   The random generator.
   */
  protected function getRandomGenerator(): Random {
    if (!$this->randomGenerator instanceof Random) {
      $this->randomGenerator = new Random();
    }
    return $this->randomGenerator;
  }

}
