<?php

namespace Drupal\view_display_field\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Views;

/**
 * Plugin implementation of the 'view_display' formatter.
 *
 * @FieldFormatter(
 *   id = "view_display",
 *   label = @Translation("View display"),
 *   field_types = {
 *     "view_display"
 *   }
 * )
 */
class ViewDisplay extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#markup' => $this->viewValue($item)
      ];
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    $value = $item->view;

    if (!intval($item->render)) {
      return $item->getString();
    }

    $value = explode('.', $value);
    $view = Views::getView($value[0]);

    if (is_object($view)) {
      $view->setDisplay($value[1]);
      $exposed_input = $view->getExposedInput();
      if ($exposed_input) {
        $view->setExposedInput($exposed_input);
      }
      $view->execute();
      $view = $view->render();
      $result = \Drupal::service('renderer')->render($view);

      return $result;
    }
  }

}
