<?php

namespace Drupal\simple_search_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\simple_search_form\Form\SimpleSearchForm;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'SimpleSearchFormBlock' block.
 *
 * @Block(
 *   id = "simple_search_form_block",
 *   admin_label = @Translation("Simple search form"),
 *   category = @Translation("Search")
 * )
 */
class SimpleSearchFormBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $formBuilder;

  /**
   * {@inheritdoc}
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Form\FormBuilderInterface $formBuilder
   *   The form builder implementation definition.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $formBuilder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $formBuilder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return $this->formBuilder->getForm(SimpleSearchForm::class, $this->getConfiguration());
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $config = parent::defaultConfiguration();

    $config['action_path'] = '';
    $config['get_parameter'] = '';
    $config['input_type'] = 'search';
    $config['input_label_display'] = 'before';
    $config['input_label'] = 'Search';
    $config['input_placeholder'] = '';
    $config['input_css_classes'] = '';
    $config['submit_display'] = TRUE;
    $config['submit_label'] = 'Find';

    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form['action_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Path'),
      '#description' => $this->t('The path to redirect to. Should start with a slash.'),
      '#required' => TRUE,
      '#default_value' => $this->configuration['action_path'],
    ];
    $form['get_parameter'] = [
      '#type' => 'textfield',
      '#title' => $this->t('GET parameter'),
      '#description' => $this->t('The $_GET parameter name.'),
      '#required' => TRUE,
      '#default_value' => $this->configuration['get_parameter'],
    ];
    $form['input_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Input element type'),
      '#options' => [
        'search' => $this->t('Search'),
        'textfield' => $this->t('Text field'),
      ],
      '#default_value' => $this->configuration['input_type'],
    ];
    $form['input_label_display'] = [
      '#type' => 'select',
      '#title' => $this->t('Label display mode'),
      '#options' => [
        'before' => $this->t('Before'),
        'after' => $this->t('After'),
        'invisible' => $this->t('Invisible'),
      ],
      '#default_value' => $this->configuration['input_label_display'],
    ];
    $form['input_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search label'),
      '#description' => $this->t('The label of a search input.'),
      '#default_value' => $this->configuration['input_label'],
    ];
    $form['input_placeholder'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search placeholder'),
      '#description' => $this->t('The placeholder for a search input.'),
      '#default_value' => $this->configuration['input_placeholder'],
    ];
    $form['input_css_classes'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search CSS classes'),
      '#description' => $this->t('Space separated list of CSS classes to add to a search input.'),
      '#default_value' => $this->configuration['input_css_classes'],
    ];
    $form['submit_display'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Display submit button'),
      '#default_value' => $this->configuration['submit_display'],
    ];
    $form['submit_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Submit label'),
      '#description' => $this->t('The label of a submit button.'),
      '#default_value' => $this->configuration['submit_label'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {
    $action_path = $form_state->getValue('action_path');
    if ($action_path[0] !== '/') {
      $form_state->setErrorByName('action_path', $this->t('Path should start with a slash.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['action_path'] = $form_state->getValue('action_path');
    $this->configuration['get_parameter'] = $form_state->getValue('get_parameter');
    $this->configuration['input_type'] = $form_state->getValue('input_type');
    $this->configuration['input_label'] = $form_state->getValue('input_label');
    $this->configuration['input_label_display'] = $form_state->getValue('input_label_display');
    $this->configuration['input_placeholder'] = $form_state->getValue('input_placeholder');
    $this->configuration['input_css_classes'] = $form_state->getValue('input_css_classes');
    $this->configuration['submit_display'] = $form_state->getValue('submit_display');
    $this->configuration['submit_label'] = $form_state->getValue('submit_label');
  }

}
