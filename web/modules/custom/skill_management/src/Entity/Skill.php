<?php

namespace Drupal\skill_management\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Skill entity.
 *
 * @ContentEntityType(
 *   id = "skill",
 *   label = @Translation("Skill"),
 *   handlers = {
 *     "list_builder" = "Drupal\skill_management\SkillListBuilder",
 *     "form" = {
 *       "default" = "Drupal\Core\Entity\ContentEntityForm",
 *       "add" = "Drupal\Core\Entity\ContentEntityForm",
 *       "edit" = "Drupal\Core\Entity\ContentEntityForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "skill",
 *   admin_permission = "administer skills",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *   },
 *   links = {
 *     "canonical" = "/admin/content/skill/{skill}",
 *     "add-form" = "/admin/content/skill/add",
 *     "edit-form" = "/admin/content/skill/{skill}/edit",
 *     "delete-form" = "/admin/content/skill/{skill}/delete",
 *     "collection" = "/admin/content/skills",
 *   },
 * )
 */
class Skill extends ContentEntityBase {
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(TRUE)
      ->setSettings(['max_length' => 255])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ]);

    $fields['category'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Category'))
      ->setRequired(FALSE)
      ->setSettings(['max_length' => 128])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 1,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 1,
      ]);

    $fields['level'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Level'))
      ->setRequired(FALSE)
      ->setSettings([
        'allowed_values' => [
          'beginner' => 'Beginner',
          'intermediate' => 'Intermediate',
          'advanced' => 'Advanced',
          'expert' => 'Expert',
        ],
      ])
      ->setDefaultValue('beginner')
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 2,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'list_default',
        'weight' => 2,
      ]);

    return $fields;
  }
}
