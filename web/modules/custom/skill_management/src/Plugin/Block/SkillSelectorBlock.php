<?php

namespace Drupal\skill_management\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Skill Selector block.
 *
 * @Block(
 *   id = "skill_selector_block",
 *   admin_label = @Translation("Skill Selector")
 * )
 */
class SkillSelectorBlock extends BlockBase {
  public function build() {
    $user = \Drupal::currentUser();
    $build = [];
    $build['#markup'] = '<div id="skill-selector-root" data-user-id="' . $user->id() . '"></div>';
    $build['#attached']['library'][] = 'skill_management/skill_selector';
    return $build;
  }
}
