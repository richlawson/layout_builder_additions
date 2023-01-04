<?php

namespace Drupal\layout_builder_additions\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\node\NodeForm;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\layout_builder_additions\Services\LayoutBuilderAdditionsTitleDisplay;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form handler for the node edit forms.
 *
 * @internal
 */
class LayoutBuilderAdditionsNodeForm extends NodeForm {

  /**
   * The tempstore factory.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * The Current User object.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Layout Builder Additions title display service.
   *
   * @var \Drupal\layout_builder_additions\Services\LayoutBuilderAdditionsTitleDisplay
   */
  protected $titleDisplay;

  /**
   * Constructs a NodeForm object.
   *
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The factory for the temp store object.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter interface.
   * @param \Drupal\layout_builder_additions\Services\LayoutBuilderAdditionsTitleDisplay $title_display
   *   The title display service.
   */
  public function __construct(EntityRepositoryInterface $entity_repository, PrivateTempStoreFactory $temp_store_factory, EntityTypeBundleInfoInterface $entity_type_bundle_info = NULL, TimeInterface $time = NULL, AccountInterface $current_user, DateFormatterInterface $date_formatter, LayoutBuilderAdditionsTitleDisplay $title_display) {
    parent::__construct($entity_repository, $temp_store_factory, $entity_type_bundle_info, $time, $current_user, $date_formatter);
    $this->tempStoreFactory = $temp_store_factory;
    $this->currentUser = $current_user;
    $this->dateFormatter = $date_formatter;
    $this->titleDisplay = $title_display;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
        $container->get('entity.repository'),
        $container->get('tempstore.private'),
        $container->get('entity_type.bundle.info'),
        $container->get('datetime.time'),
        $container->get('current_user'),
        $container->get('date.formatter'),
        $container->get('layout_builder_additions.title')
      );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $node = $this->entity;

    // Initialize variables.
    $entity_type = $node->getEntityType()->id();
    $bundle = $node->bundle();

    // Check if node bundle is enabled to customize the title display.
    if ($this->titleDisplay->checkBundle($entity_type, $bundle)) {
      // Retrieve the title display settings for the individual node.
      $node_association = $this->titleDisplay->getEntity($entity_type, $bundle, $node->id(), $node->getRevisionId());

      // Check if this node is already related to title display selection.
      if (isset($node_association->selected) && $node_association->selected == 0) {
        // If node is associated to title display selection, get selected state.
        $value = (bool) $node_association->selected;
      }
      else {
        $value = (bool) TRUE;
      }

      $form['layout_builder_additions_title_display'] = [
        '#type' => 'checkbox',
        '#title' => t('Show title'),
        '#default_value' => $value,
      ];
    }

    return parent::form($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::save($form, $form_state);

    // Get node entry.
    $node = $this->entity;
    $entity_type = $node->getEntityType()->id();
    $bundle = $node->bundle();

    // Get title display form state.
    $form_value = $form_state->getValue('layout_builder_additions_title_display');

    if (!is_null($form_value)) {
      // Upsert title display selection.
      $this->titleDisplay->upsertEntityRelationship($entity_type, $bundle, $node->id(), $node->getRevisionId(), $form_value);
    }
  }

}
