<?php

namespace Drupal\view_display_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'view_display' widget.
 *
 * @FieldWidget(
 *   id = "view_display",
 *   label = @Translation("View display"),
 *   field_types = {
 *     "view_display"
 *   }
 * )
 */
class ViewDisplay extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $views = \Drupal::entityManager()->getStorage('view')->loadMultiple();
    $options = ['' => t('- None -')];

    foreach ($views as $view) {
      foreach ($view->get('display') as $display_id => $display) {
        $options[$view->id() . '.' . $display_id] = $view->label() . ' - ' . $display_id;
      }
    }

    $element['view'] = [
      '#type' => 'select',
      '#title' => t('View'),
      '#default_value' => isset($items[$delta]->view) ? $items[$delta]->view : NULL,
      '#options' => $options,
    ];

    $element['arguments'] = [
      '#type' => 'textfield',
      '#title' => t('Arguments'),
      '#default_value' => isset($items[$delta]->arguments) ? $items[$delta]->arguments : NULL,
    ];

    return $element;
  }

}
