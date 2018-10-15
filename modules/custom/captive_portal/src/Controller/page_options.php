<?php
namespace Drupal\captive_portal\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;
use Drupal\user\Entity\User;
use Drupal\node\Entity\NodeType;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Query\EntityQueryInterface;

use Drupal\node\Entity\Node;
use Drupal\field\FieldConfigInterface;

/**
 * Provides route responses for the Example module.
 */
class page_options extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */

  public function options($user) {
    # Obtener las configuracion de los parámetros establecidos para usar en el servicio
      $config = \Drupal::config("captive_portal.bill_payment_settings");
      $group = "visualizacion";

    if($user != 'off'){
      //(userID,msisdn,userType [PRE, POS, HYB, OTHER], balance)
      $data_test = array(
        [1, '987654321', 'PRE', '7,48'],
        [2, '912345678', 'POS', '15,64'],
        [3, '923415678', 'HYB', '32,05'],
        [4, '934125678', 'OTHER', '17,80'],
        [5, '941235678', 'PRE', '47,23'],
        [6, '912346785', 'POS', '25,40'],
        [7, '912347856', 'HYB', '12,01'],
        [8, '912348567', 'OTHER', '27,75'],
      );

      # for to compare $user parameter with first position in the array
      foreach ($data_test as $k => $v) {
        if($v[0]==$user){
          #create session with user info
          $_SESSION["msisdn"]         =$v[1];
          $_SESSION["balance"]        =$v[3];
          $_SESSION["subscriber_type"]=strtolower($v[2]);

          $msisdn     =$v[1];
          $balance    =$v[3];
          $subscriber =strtolower($v[2]);
        }
      }
    }else{
      $msisdn = $balance = $subscriber = "";
      $status = 'offline';
    }


    # Add currency symbol to balance
    if($config->get($group)['payment']['position']=='pre'){
      $balance=$config->get($group)['payment']['currency']." ".$balance;
    }else{
      $balance=$balance." ".$config->get($group)['payment']['currency'];
    }

      #data to return and show in template
      return [
        '#theme' => 'captive_portal',
        '#msisdn' =>  $msisdn,
        '#balance' => $balance,
        '#subscriber' => $subscriber,
        '#status' => $status,
      ];
  }

  public function confirmation($plan) {
      # Obtener las configuracion de los parámetros establecidos para usar en el servicio
      $config = \Drupal::config("captive_portal.bill_payment_settings");
      $group = "visualizacion";

      #Get info content type from query entity
        $query = \Drupal::entityQuery('node');
        $query->condition('status', 1);
        $query->condition('type', 'captivePlan');
        $entity_ids = $query->execute();

        $nodes = node_load_multiple($entity_ids); # all result set to $nodes var
        //kint($nodes);

        foreach($nodes as $node)
        {
          $code=$node->get('field_code')->getValue();
          # if code is equal to get in parameter $plan, get info of the content type captivePlan
          if($code[0]['value']==$plan){
            $price=$node->get('field_price')->getValue();
            $title=$node->getTitle();
          }

        }

        # Add currency symbol to price
        if($config->get($group)['payment']['position']=='pre'){
          $value_price=$config->get($group)['payment']['currency']." ".$price[0]['value'];
        }else{
          $value_price=$price[0]['value']." ".$config->get($group)['payment']['currency'];
        }

        #data to return and show in Confirmation template
        return [
          '#theme' => 'confirmation-page',
          '#price' => $value_price,
          '#title_page' => $title,
          '#ret_page'   => $_SESSION["subscriber_type"],
        ];

  }


}