<?php
namespace Drupal\captive_portal\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class success_page extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function options() {    
    return [
      '#theme' => 'success_page',
      '#msisdn' => $_SESSION['msisdn'],
    ];
  }

}