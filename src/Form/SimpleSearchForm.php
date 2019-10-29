<?php

namespace Drupal\simple_search_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * SimpleSearchForm definition.
 */
class SimpleSearchForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'simple_search_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, array $config = []) {
    $form['#method'] = 'get';
    $form['#action'] = Url::fromUserInput($config['action_path'])->toString();
    $form['#token'] = FALSE;

    $form[$config['get_parameter']] = [
      '#type' => $config['input_type'],
      '#title' => $config['input_label'],
      '#title_display' => $config['input_label_display'],
      '#attributes' => [
        'placeholder' => $config['input_placeholder'],
        'class' => explode(' ', $config['input_css_classes']),
      ],
    ];

    if ($config['submit_display']) {
      $form['submit'] = [
        '#type' => 'submit',
        '#value' => $config['submit_label'],
        // Prevent op from showing up in the query string.
        '#name' => '',
      ];
    }

    // Remove after fix https://www.drupal.org/project/drupal/issues/1191278.
    $form['#after_build'][] = '::cleanupGetParams';

    return $form;
  }

  /**
   * Form #after_build callback.
   *
   * @param array $form
   *   Form to process.
   *
   * @return array
   *   Processed form.
   */
  public function cleanupGetParams(array $form) {
    // Remove all additional $_GET params from URL.
    $form['form_id']['#access'] = FALSE;
    $form['form_build_id']['#access'] = FALSE;
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Nothing to do.
  }

}
