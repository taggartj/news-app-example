<?php

namespace Drupal\news_admin\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a News api consumer plugin item annotation object.
 *
 * @see \Drupal\news_admin\Plugin\NewsApiConsumerPluginManager
 * @see plugin_api
 *
 * @Annotation
 */
class NewsApiConsumerPlugin extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
