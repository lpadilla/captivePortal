<?php

namespace Drupal\Tests\browser_push_notification\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test the configuration of keys.
 *
 * @group browser_test_notification
 */
class KeyConfigTest extends BrowserTestBase {

  public static $modules = ['browser_push_notification'];

  /**
   * Tests generating and regenerating keys.
   */
  public function testKeyConfig() {
    $this->drupalLogin($this->rootUser);
    $this->drupalGet('/admin/config/services/browser_push_notification/config');
    $this->assertSession()->statusCodeEquals(200);
    $this->drupalPostForm('/admin/config/services/browser_push_notification/config', [], t('Generate keys'));
    $this->drupalPostForm('/admin/config/services/browser_push_notification/config', [], t('Regenerate keys'));
  }

}
