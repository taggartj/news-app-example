<?php

namespace Drupal\news_admin\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines an interface for News api consumer plugin plugins.
 */
interface NewsApiConsumerPluginInterface extends PluginInspectionInterface {



  // Add get/set methods for your plugin type here.
  public function getAdminForm(array $form, FormStateInterface $form_state);

  // Save settings per plugin.
  public function savePluginSettings(array $form, FormStateInterface $form_state);


  /**
   * This is to get the full api url with auth key.
   *
   * @return string
   */
  public function getApiUrlWithKey();

  /**
   * allow for the plugin to alter the results.
   *
   * @param array $results
   *
   * @return array
   */
  public function alterResultsOutput(array$results);

}
