<?php

/**
 * @file
 * Contains \Drupal\axelerant_assignment\Controller\NodeAPIController
 */
namespace Drupal\axelerant_assignment\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Access\AccessResult;
use Symfony\Component\HttpFoundation\JsonResponse;

class nodeAPIController extends ControllerBase {
  /**
   * Returns node content in json format
   */
    public function axelerant_assignment_get_node_content($site_api_key,$node_id) {
        $data = array();
        $response = new JsonResponse();
        $node = Node::load($node_id);
        foreach($node as $key =>$value){
            $data[$key] = $node->$key->getValue();
        }

        $response->setData($data);
        return $response;
    }

/**
  * Checks access for this controller
*/
    public function axelerant_assignment_get_node_access($site_api_key,$node_id) {
	    //getting configuration varible
        $config = \Drupal::config('axelerant_assignment.settings');
        $api_key = $config->get('siteapikey');
	
        // Check node exists for 'page' content type
        $node_exists = \Drupal::entityQuery('node')->condition('nid', $node_id)->condition('type', 'page')->execute();
   
        // Allow access based on condition
        if (!empty($site_api_key) &&  ($api_key == $site_api_key) && !empty($node_exists)) {
            return AccessResult::allowed();
        }
        return AccessResult::forbidden();
    }
}