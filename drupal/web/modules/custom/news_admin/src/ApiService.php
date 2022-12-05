<?php

namespace Drupal\news_admin;
use GuzzleHttp\ClientInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\news_admin\Plugin\NewsApiConsumerPluginManager;

/**
 * Class ApiService.
 */
class ApiService implements ApiServiceInterface {

  /**
   * GuzzleHttp\ClientInterface definition.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

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

  public $activePlugins;

  /**
   * Constructs a new ApiService object.
   */
  public function __construct(ClientInterface $http_client, EntityTypeManagerInterface $entity_type_manager, NewsApiConsumerPluginManager $plugin_m) {
    $this->httpClient = $http_client;
    $this->entityTypeManager = $entity_type_manager;
    $this->pluginMannager = $plugin_m;
  }


  private function collectActivePlugins() {
    // @todo check config for which ones are actve
    $config = _news_admin_get_config();
    $activePlugins = [];
    $plugin_definitions = $this->pluginMannager->getDefinitions();
    foreach ($plugin_definitions as $plugin_definition) {
      $plugin = $this->pluginMannager->createInstance($plugin_definition['id'], []);
      $plugin->alterHttpClient($this->httpClient);

      if (empty($config)) {
        // Fall back to property @deprecated.
        // @too do throw exception here.
        if ($plugin->enabled) {
          $activePlugins[] = $plugin;
        }
      }
      else {
        // only allow selected providers
        if (!empty($config[$plugin_definition['id']])) {
          $activePlugins[] = $plugin;
        }
      }

    }
    return $activePlugins;
  }

  public function getProviders() {
    $plugin_definitions = $this->pluginMannager->getDefinitions();
    $providers = [];
    foreach($plugin_definitions as $plugin_definition) {
      $providers[] = $plugin_definition['label'];
    }
    return $providers;
  }


  /**
   * The handles the sanitised query string and does api call
   *
   * @param string $input
   *   Sanitised query string.
   * @return array
   *   Returns an array of data.
   */
  public function handleQuery(string $input) {
    $results = [];
    foreach ($this->collectActivePlugins() as $plugin) {
      $id = $plugin->getPluginId();
      $url = $plugin->getApiUrlWithKey(). '&q='. $input;
      try {
          $response = $plugin->httpClient->get($url);
          $data = json_decode($response->getBody()->getContents(), TRUE);
          if (!empty($data)) {
            $results[$id] = $plugin->alterResultsOutput($data);
          }
      } catch (\Exception $e) {
        //@todo log this.
      }
    }
    return $results;
  }

}
