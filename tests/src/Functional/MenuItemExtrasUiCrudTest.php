<?php

namespace Drupal\Tests\menu_item_extras\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;

/**
 * UI testing.
 *
 * @package Drupal\Tests\menu_item_extras\Functional
 * @group menu_item_extras
 */
class MenuItemExtrasUiCrudTest extends BrowserTestBase {

  public static $modules = ['menu_item_extras'];

  /**
   * Test UI CRUD functionality.
   */
  public function testUiMenuItemExtrasCrud() {
    // Preparations.
    $this->drupalLogin($this->rootUser);
    $createUrl = Url::fromRoute(
      'entity.menu.add_link_form',
      ['menu' => 'main']
    );
    $defaultFormValues = [
      'title[0][value]' => 'Extras Link',
      'link[0][uri]' => 'https://example.com',
      'enabled[value]' => '1',
      'description[0][value]' => 'Test',
      'body[0][value]' => '___ Menu Item Extras Field Value ___',
      'expanded[value]' => '0',
      'menu_parent' => 'main:',
      'weight[0][value]' => '10',
    ];
    $assert = $this->assertSession();
    $this->drupalGet($createUrl);
    $assert->statusCodeEquals(200);

    // Create menu item.
    $this->drupalPostForm(NULL, $defaultFormValues, 'Save');
    // Check changes.
    $menu_item_url = Url::fromRoute(
      'entity.menu_link_content.edit_form',
      ['menu_link_content' => 1]
    );
    $this->drupalGet($menu_item_url);
    $assert->fieldValueNotEquals('body[0][value]', '__ Menu Item Extras Field Value __');
    $assert->fieldValueEquals('body[0][value]', '___ Menu Item Extras Field Value ___');

    // Update menu item.
    $defaultFormValues['body[0][value]'] = '--- Menu Item Extras Field Value ---';
    $this->drupalPostForm(NULL, $defaultFormValues, 'Save');
    // Check changes.
    $this->drupalGet($menu_item_url);
    $assert->fieldValueNotEquals('body[0][value]', '___ Menu Item Extras Field Value ___');
    $assert->fieldValueEquals('body[0][value]', '--- Menu Item Extras Field Value ---');

    // Delete menu item.
    $menu_item_url = Url::fromRoute(
      'entity.menu_link_content.delete_form',
      ['menu_link_content' => 1]
    );
    $this->drupalPostForm($menu_item_url, [], 'Delete');
    // Check changes.
    $assert->pageTextContains('The menu link Extras Link has been deleted.');
  }

}
