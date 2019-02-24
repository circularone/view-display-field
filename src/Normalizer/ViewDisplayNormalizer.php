<?php

namespace Drupal\view_display_field\Normalizer;

use Drupal\serialization\Normalizer\FieldItemNormalizer;
use Drupal\view_display_field\Plugin\Field\FieldType\ViewDisplay;
use Drupal\views\Views;

/**
 * Normalizes link interface items.
 */
class ViewDisplayNormalizer extends FieldItemNormalizer {

  /**
   * {@inheritdoc}
   */
  protected $supportedInterfaceOrClass = ViewDisplay::class;

  /**
   * {@inheritdoc}
   */
  public function normalize($field_item, $format = NULL, array $context = []) {
    $value = $field_item->getValue();
    $view_id = $value['view'];
    $view_id = explode('.', $view_id);
    $view = Views::getView($view_id[0]);

    if (is_object($view)) {
      $view->setDisplay($view_id[1]);
      $exposed_input = $view->getExposedInput();

      if ($value['arguments']) {
        parse_str($value['arguments'], $arguments);

        foreach ($arguments as $key => $arg) {
          $exposed_input[$key] = $arg;
        }
      }

      if ($exposed_input) {
        $view->setExposedInput($exposed_input);
      }

      $view->execute();
      $view = $view->render();
      $result = \Drupal::service('renderer')->render($view);

      return \Drupal::service('serializer')->decode($result, 'json');
    }
  }
}
