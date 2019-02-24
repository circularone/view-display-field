<?php

namespace Drupal\view_display_field\Normalizer;

use Drupal\serialization\Normalizer\ListNormalizer;
use Drupal\view_display_field\Plugin\Field\FieldType\ViewDisplay;

/**
 * Converts list objects to arrays.
 *
 * Converts list objects of ViewDisplay fields to associative arrays.
 */
class ViewDisplayListNormalizer extends ListNormalizer {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var string
   */
  protected $supportedInterfaceOrClass = 'Drupal\Core\TypedData\ListInterface';

  /**
   * {@inheritdoc}
   */
  public function supportsNormalization($data, $format = NULL) {
    if (!is_object($data) || !$this->checkFormat($format)) {
      return FALSE;
    }

    foreach ($data as $fieldItem) {
      if ($fieldItem instanceof ViewDisplay) {
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    $attributes = [];

    foreach ($object as $fieldItem) {
      $key = str_replace('.', '__', $fieldItem->view);
      $attributes[$key] = $this->serializer->normalize($fieldItem, $format, $context);
    }

    return $attributes;
  }

}
