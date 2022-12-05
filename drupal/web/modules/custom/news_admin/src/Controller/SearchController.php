<?php

namespace Drupal\news_admin\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\news_admin\ApiService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Form\FormState;

/**
 * Class SearchController.
 */
class SearchController extends ControllerBase {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;


  /**
   * Drupal\news_admin\ApiService definition.
   *
   * @var Drupal\news_admin\ApiService
   */
  protected $apiService;


  protected $formBuilder;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->apiService = $container->get('news_admin.api_service');
    $instance->formBuilder = $container->get('form_builder');
    return $instance;
  }

  /**
   * Search.
   *
   * @return string
   *   Return build array
   */
  public function search() {
    $build = [];
    // Embed the form
    // Thanks me https://drupal.stackexchange.com/questions/182405/how-do-i-render-a-form/281468#281468
    $form_state = new FormState();
    $form_state->setRebuild();
    $build['form'] = $this->formBuilder->buildForm('Drupal\news_admin\Form\SearchNewsForm', $form_state);

    $build['form_js']['#attached']['library'][] = 'news_admin/news_search';
    $build['form_js']['#attached']['library'][] = 'news_admin/my_autocomplete';
    //$build['form_js']['#attached']['library']['drupalSettings'] = [];

    $build[] = [
      '#theme' => 'news_search',
      '#search_providers' => $this->apiService->getProviders(),
    ];

    return $build;
  }


  /**
   * Handler for autocomplete request.
   */
  public function handleAutocomplete(Request $request) {
    $results = [];
    $input = $request->query->get('q');

    if (!$input) {
      return new JsonResponse($results);
    }

    $input = Xss::filter($input);

    $api_result = $this->apiService->handleQuery($input);
    if (!empty($api_result)) {
      foreach ($api_result as $provider_result_key => $provider_results) {
        foreach ($provider_results as $data) {
           // @TODO save $data['section'] and $input
           // to later run analytics on and future scrape apis for content.
          /*
          $query =  /Drupal::entityTypeManager()->getStorage('taxonomy_term')->getQuery();
          $result = $query->condition('name', $data['section'])->execute();
          if (empty($result)) {
             // make and save new term here ect
             // Add to queue to then get via cron
          }
           */
           // @todo better to template this...
          $results[] = [
           // this would be better 'value' => $data['section'],
            'value' => $input,
            'label' =>  '<div class="auto_comp_res"> Provider:' .$provider_result_key. '<br/> <a href="'.$data['url'].'" target="_blank">
            '. $data['title'] .'</a> <b>Published: </b> '. $data['published_date']  .'
            <button  data-title="'.$data['title'].'" data-url="'.$data['url'].'"  class="save_result"> Save </button> </div>',
          ];
        }
      }
    }

    /*
    $item = 'test data';

    $results[] = [
        'value' => 'test',
        'label' =>  '<div class="auto_comp_res"> '. $item .' <button  data-title="'.$item.'" class="save_result"> Save </button> </div>',
    ];
    */
    return new JsonResponse($results);
  }




}
