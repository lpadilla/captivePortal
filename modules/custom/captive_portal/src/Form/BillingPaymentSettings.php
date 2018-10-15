<?php

namespace Drupal\captive_portal\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Class BillingPaymentSettings.
 *
 * @package Drupal\captive_portal\Form
 */
class BillingPaymentSettings extends ConfigFormBase
{
	/**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'captive_portal_payment_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'captive_portal.bill_payment_settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
  	$config = $this->config('captive_portal.bill_payment_settings');

    $form["#tree"] = true;
    $form['bootstrap'] = [
      '#type' => 'vertical_tabs',
      '#prefix' => '<h2><small>' . t('Currency Configuration Captive Portal') . '</small></h2>',
      '#weight' => -10,
      '#default_tab' => $config->get('active_tab'),
    ];


  	$group = "visualizacion";

    $form[$group] = [
      '#type' => 'details',
      '#title' => $this->t('Params'),
      '#open' => TRUE,
      '#group' => 'bootstrap'
    ];

  	$form[$group]['payment'] = array(
      '#type' => 'details',
      '#title' => t('Param settings payment'),
      '#description' => t('Parameters to use in payments'),
      '#open' => TRUE,
    );

    $form[$group]['payment']['currency'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Currency symbol'),
      '#default_value' => $config->get($group)['payment']['currency'],
    );

    $options=array('pre' => 'Prefix', 'sux' => 'Sufix');
    $form[$group]['payment']['position'] = array(
      '#type' => 'select',
      '#title' => $this->t('Position symbol'),
      '#options' => $options,
      '#default_value' => $config->get($group)['payment']['position'],
    );


    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitForm($form, $form_state);
    // Retrieve the configuration
    $this->config('captive_portal.bill_payment_settings')
      ->set('visualizacion', $form_state->getValue('visualizacion'))
      ->save();

    //drupal_set_message($this->t('The configuration options have been saved!'));
  }

}