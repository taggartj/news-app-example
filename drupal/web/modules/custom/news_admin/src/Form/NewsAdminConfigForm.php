<?php

namespace Drupal\news_admin\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\ConfigFormBase;

/**
 * Class NewsAdminConfigForm.
 */
class NewsAdminConfigForm extends ConfigFormBase  {

  /**
   * Drupal\Core\Config\ConfigManagerInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigManagerInterface
   */
  protected $configManager;

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;


  /**
   * Custom pluginMannager.
   */
  protected $pluginMannager;


   /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'news_admin.plugins',
    ];
  }



  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->configManager = $container->get('config.manager');
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->pluginMannager = $container->get('plugin.manager.news_api_consumer_plugin');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'news_admin_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('news_admin.plugins');
    $default_data = $config->getRawData();

    $plugin_definitions = $this->pluginMannager->getDefinitions();
    $defs = [];
    foreach ($plugin_definitions as $plugin_definition) {
      $plugin = $this->pluginMannager->createInstance($plugin_definition['id'], []);
      $id = $plugin_definition['id'];
      $form[$id] = $plugin->getAdminForm($form, $form_state);
      $defs[$id] = $plugin_definition['label'];
      $form[$id] = [
        '#type' => 'checkbox',
        '#title' => $this->t('@label Enabled', ['@label' => $plugin_definition['label']]),
      ];
      if (!empty($default_data[$id])) {
        $form[$id]['#default_value'] = $default_data[$id];
      }
    }


    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

   $values = $form_state->getValues();
    $config = $this->config('news_admin.plugins');

   foreach ($values as $key => $value) {
     $config->set($key, $value);
   }
   // MMM I don't have time to do this properly.
    $config->save();
   // lol I know full well this is bad but ran out of time.
    drupal_flush_all_caches();
  }

}
