<?php

namespace Drupal\skill_management\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the UserSkill entity.
 *
 * @ContentEntityType(
 *   id = "user_skill",
 *   label = @Translation("User Skill"),
 *   base_table = "user_skill",
 *   entity_keys = {
 *     "id" = "id",
 *   },
 * )
 */
class UserSkill extends ContentEntityBase {
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User'))
      ->setSetting('target_type', 'user');

    $fields['skill_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Skill'))
      ->setSetting('target_type', 'skill');

    $fields['experience'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Experience'))
      ->setDefaultValue(0);

    $fields['unit'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Unit'))
      ->setSettings(['allowed_values' => ['years' => 'years', 'months' => 'months']])
      ->setDefaultValue('years');

    return $fields;
  }
}
