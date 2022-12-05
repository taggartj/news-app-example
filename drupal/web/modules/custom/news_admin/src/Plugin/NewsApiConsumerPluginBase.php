<?php

namespace Drupal\news_admin\Plugin;

use Drupal\Component\Plugin\PluginBase;
use GuzzleHttp\ClientInterface;
/**
 * Base class for News api consumer plugin plugins.
 */
abstract class NewsApiConsumerPluginBase extends PluginBase implements NewsApiConsumerPluginInterface {

  /**
   * GuzzleHttp\ClientInterface definition.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  public $httpClient;

  public function getApiKey() {
    return $this->apiKey;
  }

  public function getBaceUrl() {
    return $this->apiUrl;
  }


  public function validatePlugin() {
    if (empty($this->getApiKey()) || empty($this->getApiKey())) {
      return false;
    }
    if (filter_var($this->getBaceUrl(), FILTER_VALIDATE_URL) === false) {
      return false;
    }
    return true;
  }

  /**
   * Here we could alter the client. for auth headers ect.
   *
   * @param \GuzzleHttp\ClientInterface $httpClient
   *
   * @return \GuzzleHttp\ClientInterface
   */
  public function alterHttpClient(ClientInterface $httpClient) {
    if (!$this->httpClient) {
      $this->httpClient = $httpClient;
      // @too so alter ect
    }
    return $httpClient;
  }

}
