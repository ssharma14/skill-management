<?php

namespace Drupal\skill_management\Controller;

use Drupal\Core\Controller\ControllerBase;

class SkillPageController extends ControllerBase {

  /**
   * Main skill management page.
   */
  public function main() {
    // Get the current user ID
    $current_user = \Drupal::currentUser();
    $user_id = $current_user->id();

    // Build the page using Drupal's render array
    $build = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => ['class' => ['container']],
      '#attached' => [
        'library' => [
          'skill_management/skill_selector',
        ],
      ],
      'title' => [
        '#type' => 'html_tag',
        '#tag' => 'h1',
        '#value' => $this->t('Skill Management System'),
      ],
      'subtitle' => [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => ['class' => ['subtitle']],
        '#value' => $this->t('Search and add skills to your profile'),
      ],
      'react_root' => [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#attributes' => [
          'id' => 'skill-selector-root',
          'data-user-id' => $user_id,
        ],
      ],
    ];

    return $build;
  }
}
