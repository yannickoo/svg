<?php

/**
 * @file
 * Contains Drupal\svg\Form\ConfigForm.
 */

namespace Drupal\svg\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Serialization\Exception\InvalidDataTypeException;
use Drupal\Component\Serialization\Yaml;

/**
 * Class ConfigForm.
 *
 * @package Drupal\svg\Form
 */
class ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'svg.config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'svg_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('svg.config');
    $conf = $config->get();
    $config_text = Yaml::encode($conf);

    if (!\Drupal::moduleHandler()->moduleExists('yaml_editor')) {
      $message = $this->t('It is recommended to install the <a href="@yaml-editor">YAML Editor</a> module for easier editing.', [
        '@yaml-editor' => 'https://www.drupal.org/project/yaml_editor',
      ]);

      drupal_set_message($message, 'warning');
    }

    $form['config'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Configuration'),
      '#default_value' => $config_text,
      '#attributes' => ['data-yaml-editor' => 'true'],
    );

    $form['example'] = [
      '#type' => 'details',
      '#title' => $this->t('Example structure'),
    ];
    $form['example']['description'] = [
      '#prefix' => '<p>',
      '#suffix' => '</p>',
      '#markup' => $this->t('Each SVG file name has a path. Use <code>@theme_name</code> for referring to a theme.')
    ];
    $form['example']['code'] = [
      '#prefix' => '<pre>',
      '#suffix' => '</pre>',
      '#markup' => "mappings:
  logo:
    path: '@bartik/logo.svg'",
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $config_text = $form_state->getValue('config') ?: 'mappings:';
    try {
      $form_state->set('config', Yaml::decode($config_text));
    }
    catch (InvalidDataTypeException $e) {
      $form_state->setErrorByName('config', $e->getMessage());
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $form_state->get('config');
    $this->config('svg.config')
      ->setData($config)
      ->save();

    parent::submitForm($form, $form_state);
  }
}
