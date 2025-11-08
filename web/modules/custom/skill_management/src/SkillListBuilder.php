<?php

namespace Drupal\skill_management;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Skill entities.
 */
class SkillListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Name');
    $header['category'] = $this->t('Category');
    $header['level'] = $this->t('Level');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\skill_management\Entity\Skill $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->get('name')->value,
      'entity.skill.edit_form',
      ['skill' => $entity->id()]
    );
    $row['category'] = $entity->get('category')->value ?: '-';
    $row['level'] = $entity->get('level')->value ?: 'beginner';
    return $row + parent::buildRow($entity);
  }

}
