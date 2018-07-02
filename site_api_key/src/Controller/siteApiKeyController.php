<?php

/**
 * @file
 * Contains \Drupal\site_api_key\Controller\siteApiKeyController.
 */
namespace Drupal\site_api_key\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * Return Response for site api key routes
 */
class siteApiKeyController extends ControllerBase {
  /**
   * Check site api key and node type to respose in json.
   *
   * @param string $site_api_key
   * A site api key stored on site information page.
   * @param $id
   * A Node Id
   * @return jsonResponse 
   * A json repsonse of node.
   */
  public function node_to_json($route_site_api_key, $id){
  	//Get the site api key
    $site_api_key = \Drupal::config('system.site')->get('site_api_key');
    
    // check site key is valid or not.
    if($route_site_api_key != '' && strtolower($site_api_key) == strtolower($route_site_api_key)) {
    	// Check for valide node id
    	if(is_numeric($id)) {
    	  // Load node form node id.
    	  $node = \Drupal\node\Entity\Node::load($id);
    	  // check node is valid and type of node.
    	  if(isset($node) && $node->getType() == 'page'){
    	  	$serializer = \Drupal::service('serializer');
    	  	// Build appropriate JSON response
            $data = $serializer->normalize($node, 'hal_json');
            return new JsonResponse ($data);
    	  } else {
    	  	throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
    	  }
		} else {
		  throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();	
		}    	
    } else {
    	throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
    }
  }
}