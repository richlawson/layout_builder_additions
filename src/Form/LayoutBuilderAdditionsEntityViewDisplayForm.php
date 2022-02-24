<?php

namespace Drupal\layout_builder_additions\Form;

use Drupal\Component\Plugin\PluginManagerBase;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\layout_builder\Form\LayoutBuilderEntityViewDisplayForm;
use Drupal\layout_builder_additions\Services\LayoutBuilderAdditionsTitleDisplay;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Extends form for the LayoutBuilderEntityViewDisplay entity type.
 *
 * @internal
 *   Form classes are internal.
 */
class LayoutBuilderAdditionsEntityViewDisplayForm extends LayoutBuilderEntityViewDisplayForm {

  /**
   * Layout Builder Additions title display service.
   *
   * @var \Drupal\layout_builder_additions\Services\LayoutBuilderAdditionsTitleDisplay
   */
  protected $titleDisplay;

  /**
   * Constructs a NodeForm object.
   *
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $field_type_manager
   *   The interface for the field type plugin manager.
   * @param \Drupal\Component\Plugin\PluginManagerBase $plugin_manager
   *   The base class for plugin managers.
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository
   *   The interface for an entity display repository.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The interface for an entity field manager.
   * @param \Drupal\layout_builder_additions\Services\LayoutBuilderAdditionsTitleDisplay $title_display
   *   The title display service.
   */
  public function __construct(FieldTypePluginManagerInterface $field_type_manager, PluginManagerBase $plugin_manager, EntityDisplayRepositoryInterface $entity_display_repository, EntityFieldManagerInterface $entity_field_manager, LayoutBuilderAdditionsTitleDisplay $title_display) {
    parent::__construct($field_type_manager, $plugin_manager, $entity_display_repository, $entity_field_manager, $title_display);
    $this->title_display = $title_display;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.field.field_type'),
      $container->get('plugin.manager.field.formatter'),
      $container->get('entity_display.repository'),
      $container->get('entity_field.manager'),
      $container->get('layout_builder_additions.title')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $entity_type = $this->entityTypeManager->getDefinition($this->entity->getTargetEntityTypeId());
    $form['layout']['title_display'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable each @entity to have its title display customized.', [
        '@entity' => $entity_type->getSingularLabel(),
      ]),
      '#default_value' => $this->entity->isOverridable(),
      '#states' => [
        'disabled' => [
          [
            ':input[name="layout[enabled]"]' => ['checked' => FALSE],
          ],
          [
            ':input[name="layout[allow_custom]"]' => ['checked' => FALSE],
          ],
        ],
        'invisible' => [
          [
            ':input[name="layout[enabled]"]' => ['checked' => FALSE],
          ],
          [
            ':input[name="layout[allow_custom]"]' => ['checked' => FALSE],
          ],
        ],
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $type = $this->entity;

    $title_display = $form_state->getValue(['layout']['title_display']);
    $value = $title_display['layout']['title_display'];

    parent::save($form, $form_state);
  }

}
