<?php

namespace Drupal\menu_item_extras\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class MenuLinkContentHelper.
 *
 * @package Drupal\menu_item_extras\Service
 */
class MenuLinkContentService implements MenuLinkContentServiceInterface {

  private $entityTypeManager;

  /**
   * MenuLinkContentHelper constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public function updateMenuItemsBundle($menu_id, $extras_enabled = TRUE) {
    /** @var \Drupal\menu_link_content\MenuLinkContentInterface[] $menu_links */
    $menu_links = $this->entityTypeManager
      ->getStorage('menu_link_content')
      ->loadByProperties(['menu_name' => $menu_id]);
    if (!empty($menu_links)) {
      foreach ($menu_links as $menu_link) {
        if ($extras_enabled && $menu_link->bundle() === 'menu_link_content') {
          $menu_link->set('bundle', $menu_id)->save();
        }
        elseif (!$extras_enabled && $menu_link->bundle() !== 'menu_link_content') {
          $menu_link->set('bundle', 'menu_link_content')->save();
        }
      }
    }
  }

}
