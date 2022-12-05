<?php

namespace Drupal\news_admin\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the News api consumer plugin plugin manager.
 */
class NewsApiConsumerPluginManager extends DefaultPluginManager {


  /**
   * Constructs a new NewsApiConsumerPluginManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/NewsApiConsumerPlugin', $namespaces, $module_handler, 'Drupal\news_admin\Plugin\NewsApiConsumerPluginInterface', 'Drupal\news_admin\Annotation\NewsApiConsumerPlugin');

    $this->alterInfo('news_admin_news_api_consumer_plugin_info');
    $this->setCacheBackend($cache_backend, 'news_admin_news_api_consumer_plugin_plugins');
  }

}
