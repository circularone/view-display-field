<?php

namespace Drupal\view_display_field\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'view_display' field type.
 *
 * @FieldType(
 *   id = "view_display",
 *   label = @Translation("View display"),
 *   description = @Translation("My Field Type"),
 *   default_widget = "view_display",
 *   default_formatter = "view_display"
 * )
 */
class ViewDisplay extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'view' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
        ],
        'arguments' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $view = $this->get('view')->getValue();
    return $view = NULL || empty($view);
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['view'] = DataDefinition::create('string')
      ->setLabel(t('View'))
      ->setDescription(t('The ID of the selected view display'));
    $properties['arguments'] = DataDefinition::create('string')
      ->setLabel(t('Arguments'))
      ->setDescription(t('Default arguments to apply to the view'));

    return $properties;
  }

}
