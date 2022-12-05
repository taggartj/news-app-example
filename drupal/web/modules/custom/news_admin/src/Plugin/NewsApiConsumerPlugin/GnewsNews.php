<?php


namespace Drupal\news_admin\Plugin\NewsApiConsumerPlugin;

use Drupal\news_admin\Plugin\NewsApiConsumerPluginBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use GuzzleHttp\ClientInterface;


/**
 * Provides a 'gnews.io news';
 *
 * @NewsApiConsumerPlugin(
 *   id = "gnews_io",
 *   label = "gnews.io news api sourse",
 * )
 */
class GnewsNews extends NewsApiConsumerPluginBase {

    public $apiUrl = "https://gnews.io/api/v4/search?lang=en&country=us&max=5";

    // this will be gotten form setting
    public $apiKey = 'a08c125c82e7d1779b71d843dd7d2501';

    public $enabled = true;

    public function getApiUrlWithKey() {
      return $this->apiUrl. '&token=' . $this->apiKey;
    }


    public function getAdminForm(array $form, FormStateInterface $form_state) {
      return $form;
    }



    public function savePluginSettings(array $form, FormStateInterface $form_state) {
        //@todo save settings in the future.
    }


    public function alterResultsOutput($results) {
      if (!empty($results['articles'])) {
        $results =  $results['articles'];
      }
      $formatted_results = [];
      if (!empty($results)) {
        foreach ($results as $key => $result) {
          $formatted_results[$key] = [
            'title' => $result['title'],
            'published_date' => $result['publishedAt'],
            'section' => $result['source']['name'],
            'url' => $result['url'],
            'api_url' => $result['url'],
          ];
        }
      }
      return $formatted_results;
    }



}
