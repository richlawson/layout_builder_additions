<?php

namespace Drupal\layout_builder_additions;

use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\layout_builder\SectionStorageInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Simple class to set the title on Layout Builder block forms.
 */
class LayoutBuilderBlockFormTitle implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The block manager.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  protected $blockManager;

  /**
   * Constructs a LayoutBuilderBlockFormTitle instance.
   *
   * @param \Drupal\Core\Block\BlockManagerInterface $block_manager
   *   The block manager.
   */
  public function __construct(BlockManagerInterface $block_manager) {
    $this->blockManager = $block_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.block')
    );
  }

  /**
   * Set the title for a Layout Builder Add Block form.
   *
   * @param \Drupal\layout_builder\SectionStorageInterface $section_storage
   *   The section storage being configured.
   * @param int $delta
   *   The delta of the section.
   * @param string $region
   *   The region of the block.
   * @param string|null $plugin_id
   *   The plugin ID of the block to add.
   *
   * @return string
   *   The updated title.
   */
  public function addBlock(SectionStorageInterface $section_storage = NULL, $delta = NULL, $region = NULL, $plugin_id = NULL) {
    if (strpos('inline_block:', $plugin_id) === FALSE) {
      $label = $this->getLabelFromPluginId($plugin_id);
      if (!empty($label)) {
        return $label;
      }
    }

    // Default to just showing the default string.
    return $this->t('Configure block');
  }

  /**
   * Set the title for a Layout Builder Add Block form.
   *
   * @param \Drupal\layout_builder\SectionStorageInterface $section_storage
   *   The section storage being configured.
   * @param int $delta
   *   The delta of the section.
   * @param string $region
   *   The region of the block.
   * @param string $uuid
   *   The UUID of the block being updated.
   *
   * @return string
   *   The updated title.
   */
  public function updateBlock(SectionStorageInterface $section_storage = NULL, $delta = NULL, $region = NULL, $uuid = NULL) {
    // Load the component that is being modified.
    $component = $section_storage->getSection($delta)->getComponent($uuid);

    // Try loading the component's configuration, which should have an "id"
    // string in the format "inline_block:BLOCKTYPE".
    $config = $component->get('configuration');
    if (!empty($config) && !empty($config['id'])) {
      $label = $this->getLabelFromPluginId($config['id']);
      if (!empty($label)) {
        return $label;
      }
    }

    // Default to just showing the default string.
    return $this->t('Configure block');
  }

  /**
   * Get the full label of a block content bundle type.
   *
   * @param string $plugin_id
   *   Is expected to be "inline_block:" and the block content's bundle.
   *
   * @return string
   *   The full label of the block type extracted from the plugin ID.
   */
  protected function getLabelFromPluginId($plugin_id) {
    if ($plugin_block = $this->blockManager->createInstance($plugin_id, [])) {
      $definition = $plugin_block->getPluginDefinition();
      if (!empty($definition['admin_label'])) {
        return $this->t('Configure block: %label', ['%label' => $definition['admin_label']]);
      }
    }

    $bundle = str_replace('inline_block:', '', $plugin_id);
    if (!empty($bundle)) {
      // Default to the bundle name.
      $label = $bundle;

      // Try to load the block bundle's full label.
      $bundle_entity = \Drupal::entityTypeManager()
        ->getStorage('block_content_type')
        ->load($bundle);
      if (!empty($bundle_entity)) {
        $label = $bundle_entity->label();
      }

      return $this->t('Configure block: %label', ['%label' => $label]);
    }
    else {
      // This should not be a valid case.
      return $this->t('Configure block');
    }
  }

}
