<?php

namespace Drupal\Tests\fotorama_gallery\FunctionalJavascript;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;

/**
 * Provides a helper method for creating Image fields.
 */
trait FotoramaGalleryImageFieldCreationTrait {

  /**
   * Create a new image field.
   *
   * @param string $name
   *   The name of the new field (all lowercase), exclude the "field_" prefix.
   * @param string $type_name
   *   The node type that this field will be added to.
   * @param array $storage_settings
   *   (optional) A list of field storage settings that will be added to the
   *   defaults.
   * @param array $field_settings
   *   (optional) A list of instance settings that will be added to the instance
   *   defaults.
   * @param array $widget_settings
   *   (optional) Widget settings to be added to the widget defaults.
   * @param array $formatter_settings
   *   (optional) Formatter settings to be added to the formatter defaults.
   * @param string $description
   *   (optional) A description for the field.
   *
   * @return \Drupal\Core\Entity\EntityInterface|static
   *   A new entity object.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function createImageField($name, $type_name, array $storage_settings = [], array $field_settings = [], array $widget_settings = [], array $formatter_settings = [], $description = '') {
    FieldStorageConfig::create([
      'field_name' => $name,
      'entity_type' => 'node',
      'type' => 'image',
      'settings' => $storage_settings,
      'cardinality' => !empty($storage_settings['cardinality']) ? $storage_settings['cardinality'] : 1,
    ])->save();

    $field_config = FieldConfig::create([
      'field_name' => $name,
      'label' => $name,
      'entity_type' => 'node',
      'bundle' => $type_name,
      'required' => !empty($field_settings['required']),
      'settings' => $field_settings,
      'description' => $description,
    ]);
    $field_config->save();

    $entity_type = 'node';
    $view_mode = 'default';

    \Drupal::entityTypeManager()
      ->getStorage('entity_form_display')
      ->load($entity_type . '.' . $type_name . '.' . $view_mode)
      ->setComponent($name, [
        'type' => 'image_image',
        'settings' => $widget_settings,
      ])
      ->save();

    \Drupal::entityTypeManager()
      ->getStorage('entity_view_display')
      ->load($entity_type . '.' . $type_name . '.' . $view_mode)
      ->setComponent($name, [
        'type' => 'fotorama_gallery',
        'settings' => $formatter_settings,
      ])
      ->save();

    return $field_config;
  }

}
