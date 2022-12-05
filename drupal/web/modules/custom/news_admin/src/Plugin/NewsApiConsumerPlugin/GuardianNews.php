<?php

namespace Drupal\news_admin\Plugin\NewsApiConsumerPlugin;

use Drupal\news_admin\Plugin\NewsApiConsumerPluginBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use GuzzleHttp\ClientInterface;


/**
 * Provides a 'Guardian news';
 *
 * @NewsApiConsumerPlugin(
 *   id = "guardian",
 *   label = "Guardian news api sourse",
 * )
 */
class GuardianNews extends NewsApiConsumerPluginBase {

    public $apiUrl = 'https://content.guardianapis.com/search';

    // this will be gotten form setting
    public $apiKey = 'test';

    public $enabled = true;


  public function getApiUrlWithKey() {
      return $this->apiUrl.'?api-key=' . $this->apiKey;
    }

    public function getAdminForm(array $form, FormStateInterface $form_state) {
        return $form;
    }

    public function savePluginSettings(array $form, FormStateInterface $form_state) {
        //@todo save settings in the future.
    }


    public function alterResultsOutput($results) {
      if (!empty($results['response']['results'])) {
        $results =  $results['response']['results'];
      }
      $formatted_results = [];
      if (!empty($results)) {
        foreach ($results as $key => $result) {
          $formatted_results[$key] = [
            'title' => $result['webTitle'],
            'published_date' => $result['webPublicationDate'],
            'section' => $result['sectionId'],
            'url' => $result['webUrl'],
            'api_url' => $result['apiUrl'],
          ];
        }
      }
      return $formatted_results;
    }



}
