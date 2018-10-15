<?php

namespace Drupal\browser_push_notification\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Minishlink\WebPush\VAPID;

/**
 * Configure example settings for this site.
 */
class PushNotificationConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'browser_push_notification_config_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'browser_push_notification.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('browser_push_notification.settings');

    $form['description'] = [
      '#markup' => 'Application Server Keys. Please copy keys from https://web-push-codelab.appspot.com',
      '#description' => $this->t('Public Key'),
    ];

    $form['bpn_public_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Public Key'),
      '#maxlength' => 100,
      '#default_value' => $config->get('bpn_public_key'),
    ];

    $form['bpn_private_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Private Key'),
      '#maxlength' => 100,
      '#default_value' => $config->get('bpn_private_key'),
    ];

    $form['actions']['generate'] = [
      '#type' => 'submit',
      '#value' => $config->get('bpn_public_key') ? $this->t('Regenerate keys') : $this->t('Generate keys'),
      '#submit' => ['::generateKeys'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    parent::submitForm($form, $form_state);
    $this->config('browser_push_notification.settings')
      ->set('bpn_public_key', $form_state->getValue('bpn_public_key'))
      ->set('bpn_private_key', $form_state->getValue('bpn_private_key'))
      ->save();
  }

  /**
   * Form submission handler for generating keys.
   */
  public function generateKeys(array &$form, FormStateInterface $form_state) {
    $keys = VAPID::createVapidKeys();
    $this->config('browser_push_notification.settings')
      ->set('bpn_public_key', $keys['publicKey'])
      ->set('bpn_private_key', $keys['privateKey'])
      ->save();
  }

}
