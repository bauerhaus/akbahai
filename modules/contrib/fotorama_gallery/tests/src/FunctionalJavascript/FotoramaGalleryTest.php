<?php

namespace Drupal\Tests\fotorama_gallery\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Tests Fotorama Gallery functionality.
 *
 * @group fotorama_gallery
 */
class FotoramaGalleryTest extends WebDriverTestBase {

  use FotoramaGalleryImageFieldCreationTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'node',
    'field',
    'field_ui',
    'block',
    'fotorama_gallery',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Create a user with permissions to manage.
    $permissions = [
      'administer site configuration',
      'administer content types',
      'administer nodes',
      'administer node fields',
      'administer node form display',
      'administer node display',
    ];

    $account = $this->drupalCreateUser($permissions);

    // Initiate user session.
    $this->drupalLogin($account);

    // Breadcrumb is required for FieldUiTestTrait::fieldUIAddNewField.
    $this->drupalPlaceBlock('system_breadcrumb_block');
  }

  /**
   * Tests enabling a resource and accessing it.
   */
  public function testFotoramaGalleryFormatterImageField() {

    // Create a Content type.
    $this->drupalCreateContentType(['type' => 'gallery', 'name' => 'Gallery']);

    // Add a image field to the Gallery content type.
    $storage_settings = ['cardinality' => 0];
    $field_settings = ['required' => 0];
    $this->createImageField('field_image_test_gallery', 'gallery', $storage_settings, $field_settings);

    // Go to display form.
    $this->drupalGet('admin/structure/types/manage/gallery/display');

    // @var $manage_display_page WebDriverWebAssert
    $manage_display_page = $this->assertSession();

    // Check if the field was created with the correct formatter.
    $manage_display_page->selectExists('fields[field_image_test_gallery][type]');
    $manage_display_page->fieldValueEquals('fields[field_image_test_gallery][type]', 'fotorama_gallery');

    // Check if field settings summary exist.
    $manage_display_page->pageTextContains('Fotorama Gallery Settings');
    $manage_display_page->pageTextContains('data-fit: contain');

    // Open form Format settings: Fotorama.
    $this->click('[data-drupal-selector="edit-fields-field-image-test-gallery-settings-edit"]');
    $manage_display_page->waitForElementVisible('css', '[data-drupal-selector="edit-fields-field-image-test-gallery-settings-edit-form"]');

    // Fill Width field in Dimensions group.
    $this->click('[data-drupal-selector="edit-fields-field-image-test-gallery-settings-edit-form-settings-dimensions"]');
    $manage_display_page->waitForElementVisible('css', '[data-drupal-selector="edit-fields-field-image-test-gallery-settings-dimensions-width"]');
    $this->getSession()->getPage()->fillField('fields[field_image_test_gallery][settings_edit_form][settings][dimensions][width]', '500');

    // Save form Format settings: Fotorama.
    $this->click('[data-drupal-selector="edit-fields-field-image-test-gallery-settings-edit-form-actions-save-settings"]');

    // Check if the summary is updated correctly.
    $manage_display_page->waitForText('Fotorama Gallery Settings');
    $manage_display_page->pageTextContains('data-width: 500');

    $this->createScreenshot(\Drupal::root() . '/sites/default/files/simpletest/screen-form-display.png');

    // Save Manage display form.
    $this->click('[data-drupal-selector="edit-submit"]');

    $manage_display_page->pageTextContains('Your settings have been save');
  }

}
