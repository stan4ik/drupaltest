<?php

namespace Drupal\Tests\button_field\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\field_ui\Traits\FieldUiTestTrait;
use Drupal\Tests\node\Traits\ContentTypeCreationTrait;

/**
 * Tests installing button_field and creating a field in the UI.
 *
 * @group button_field
 */
class ButtonFieldAdminTest extends BrowserTestBase {

  use ContentTypeCreationTrait;
  use FieldUiTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'field',
    'field_ui',
    'button_field',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * The content type.
   *
   * @var \Drupal\node\Entity\NodeType
   */
  protected $contentType;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->contentType = $this->drupalCreateContentType();

    $adminUser = $this->createUser([
      'access content',
      'administer node fields',
      'administer node form display',
      'administer node display',
      'administer content types',
      'administer site configuration',
      'access administration pages',
    ]);

    $this->drupalLogin($adminUser);
  }

  /**
   * Asserts that a button field is created on a node type.
   *
   * @throws \Behat\Mink\Exception\ResponseTextException
   */
  public function testCreateButtonField() {
    $this->drupalGet('/admin/structure/types/manage/' . $this->contentType->id() . '/fields/add-field');
    $edit = [
      'new_storage_type' => 'button_field',
      'label' => $this->randomString(),
      'field_name' => strtolower($this->randomMachineName()),
    ];
    $this->submitForm($edit, 'Save and continue');
    $this->submitForm([], 'Save field settings');
    $widget_settings = [
      'label' => $edit['label'],
      'description' => '',
      'required' => 0,
      'settings[confirmation]' => '',
    ];
    $this->submitForm($widget_settings, 'Save settings');

    $this->assertSession()
      ->pageTextContains('Saved ' . $edit['label'] . ' configuration.');

    $this->drupalGet('/admin/structure/types/manage/' . $this->contentType->id() . '/fields/node.' . $this->contentType->id() . '.field_' . $edit['field_name'] . '/delete');
    $this->submitForm([], 'Delete');
    $this->assertSession()
      ->pageTextContains('The field ' . $edit['label'] . ' has been deleted from the ' . $this->contentType->label() . ' content type.');
  }

}
