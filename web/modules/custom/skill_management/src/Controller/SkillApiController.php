<?php

namespace Drupal\skill_management\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SkillApiController extends ControllerBase {
  public function search(Request $request) {
    $q = $request->query->get('search', '');

    $query = \Drupal::entityQuery('skill')
      ->accessCheck(FALSE)
      ->condition('name', '%' . $q . '%', 'LIKE')
      ->range(0, 20);
    $ids = $query->execute();

    $items = [];
    if (!empty($ids)) {
      $skills = \Drupal::entityTypeManager()->getStorage('skill')->loadMultiple($ids);
      foreach ($skills as $s) {
        $items[] = [
          'id' => $s->id(),
          'name' => $s->get('name')->value,
          'category' => $s->get('category')->value ?: '',
          'level' => $s->get('level')->value ?: 'beginner',
        ];
      }
    }

    return new JsonResponse($items);
  }

  public function getUserSkills(Request $request) {
    $uid = $request->query->get('user_id');

    if (!isset($uid)) {
      return new JsonResponse(['error' => 'User ID required'], 400);
    }

    $uid = (int) $uid;

    // Load user skills
    $query = \Drupal::entityQuery('user_skill')
      ->accessCheck(FALSE)
      ->condition('user_id.target_id', $uid);
    $ids = $query->execute();

    $items = [];
    if (!empty($ids)) {
      $user_skills = \Drupal::entityTypeManager()->getStorage('user_skill')->loadMultiple($ids);
      $skill_storage = \Drupal::entityTypeManager()->getStorage('skill');

      foreach ($user_skills as $us) {
        // Get target_id from entity reference field
        $skill_id = $us->get('skill_id')->target_id;
        $skill = $skill_storage->load($skill_id);

        if ($skill) {
          $items[] = [
            'id' => $skill->id(),
            'name' => $skill->get('name')->value,
            'category' => $skill->get('category')->value ?: '',
            'level' => $skill->get('level')->value ?: 'beginner',
            'experience' => $us->get('experience')->value,
            'unit' => $us->get('unit')->value ?: 'years',
            'saved' => true,
          ];
        }
      }
    }

    return new JsonResponse($items);
  }

  public function saveUserSkills(Request $request) {
    $data = json_decode($request->getContent(), TRUE);
    if (!isset($data['user_id']) || !isset($data['skills']) || !is_array($data['skills'])) {
      return new JsonResponse(['error' => 'Invalid payload'], 400);
    }

    $uid = (int) $data['user_id'];

    // Delete existing for user.
    $existing = \Drupal::entityQuery('user_skill')
      ->accessCheck(FALSE)
      ->condition('user_id.target_id', $uid)
      ->execute();
    if (!empty($existing)) {
      $storage = \Drupal::entityTypeManager()->getStorage('user_skill');
      $ents = $storage->loadMultiple($existing);
      $storage->delete($ents);
    }

    $storage = \Drupal::entityTypeManager()->getStorage('user_skill');
    foreach ($data['skills'] as $s) {
      $entity = $storage->create([
        'experience' => isset($s['experience']) ? (int) $s['experience'] : 0,
        'unit' => isset($s['unit']) ? $s['unit'] : 'years',
      ]);
      // Set entity reference fields using target_id
      $entity->set('user_id', ['target_id' => $uid]);
      $entity->set('skill_id', ['target_id' => $s['id']]);
      $entity->save();

      // Update the skill level if provided
      if (isset($s['level'])) {
        $skill = \Drupal::entityTypeManager()->getStorage('skill')->load($s['id']);
        if ($skill) {
          $skill->set('level', $s['level']);
          $skill->save();
        }
      }
    }

    return new JsonResponse(['status' => 'ok', 'message' => 'Skills saved successfully']);
  }
}
