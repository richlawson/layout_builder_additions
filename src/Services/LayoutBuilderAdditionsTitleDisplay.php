<?php

namespace Drupal\layout_builder_additions\Services;

use Drupal\Core\Database\Connection;

/**
 * Entity handling for Layout Builder Additions title display options.
 *
 * @internal
 */
class LayoutBuilderAdditionsTitleDisplay {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Construct a repository object.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * Get entity and title display relationship.
   *
   * @param string $entity_type
   *   Variable containing the entity type id machine name of the bundle.
   * @param string $bundle
   *   Variable containing the bundle machine name.
   * @param int $entity_id
   *   Variable containing the entity id.
   * @param int $revision_id
   *   Variable containing the revision id.
   *
   * @see Drupal\Core\Database\Connection::select()
   *
   * @return object
   *   Object containing title display and entity relationship.
   */
  public function getEntity($entity_type = 'node', $bundle = 'node', $entity_id = NULL, $revision_id = NULL) {
    $entities = $this->connection->select('layout_builder_additions_title_display_entity', 'entity')
      ->condition('entity_type', $entity_type)
      ->condition('bundle', $bundle)
      ->fields('entity');

    if (!is_null($entity_id)) {
      $entities->condition('entity_id', $entity_id);
    }

    if (!is_null($revision_id)) {
      $entities->condition('revision_id', $revision_id);
    }

    $results = $entities->execute();

    $saved_entity_relation = [];
    foreach ($results as $revision_id => $result) {
      $saved_entity_relation = $result;
    }
    return $saved_entity_relation;
  }

  /**
   * Check if this is a title display bundle in the database.
   *
   * @param string $entity_type
   *   Variable containing the entity type machine name.
   * @param string $bundle
   *   Variable containing the bundle machine name.
   *
   * @see Drupal\Core\Database\Connection::select()
   *
   * @return bool
   *   Boolean value of whether or not the bundle exists.
   */
  public function checkBundle($entity_type = NULL, $bundle = NULL) {
    $bundles = $this->connection->select('layout_builder_additions_title_display_bundle', 'bundle')
      ->fields('bundle');

    if (!is_null($entity_type)) {
      $bundles->condition('entity_type', $entity_type);
    }

    if (!is_null($bundle)) {
      $bundles->condition('bundle', $bundle);
    }

    $bundle_exists = FALSE;
    $results = $bundles->execute();
    foreach ($results as $entity_id => $result) {
      if ($result->bundle == $bundle) {
        $bundle_exists = TRUE;
      }
    }
    return $bundle_exists;
  }

  /**
   * Insert an entry into the database.
   *
   * @param string $entity_type
   *   Variable containing the entity type machine name.
   * @param string $bundle
   *   Variable containing the bundle machine name.
   *
   * @see \Drupal\Core\Database\Connection::insert()
   */
  public function insertBundle($entity_type = NULL, $bundle = NULL) {

    $this->connection->insert('layout_builder_additions_title_display_bundle')
      ->fields([
        'entity_type' => $entity_type,
        'bundle' => $bundle,
      ])
      ->execute();
  }

  /**
   * Delete an entry from the database.
   *
   * @param string $entity_type
   *   Variable containing the entity type machine name.
   * @param string $bundle
   *   Variable containing the bundle machine name.
   *
   * @see Drupal\Core\Database\Connection::delete()
   */
  public function deleteBundle($entity_type, $bundle) {
    $this->connection->delete('layout_builder_additions_title_display_bundle')
      ->condition('entity_type', $entity_type)
      ->condition('bundle', $bundle)
      ->execute();
  }

  /**
   * Insert an entry into the database.
   *
   * @param string $entity_type
   *   Variable containing the entity type machine name.
   * @param string $bundle
   *   Variable containing the bundle machine name.
   * @param int $entity_id
   *   Variable containing the entity id.
   * @param int $revision_id
   *   Variable containing the revision id.
   * @param int $selected
   *   Variable containing default title display selection.
   *
   * @see Drupal\Core\Database\Connection::insert()
   *
   * @return int
   *   Inserted entity id.
   */
  public function insertEntityRelationship($entity_type = 'node', $bundle = 'article', $entity_id = NULL, $revision_id = NULL, $selected = 1) {

    $fields = [
      'entity_type' => $entity_type,
      'bundle' => $bundle,
      'entity_id' => (int) $entity_id,
      'revision_id' => (int) $revision_id,
    ];

    if ($selected) {
      $selected = (bool) $selected;
      $fields['selected'] = (int) $selected;
    }

    $insert = $this->connection->insert('layout_builder_additions_title_display_entity');
    $insert->fields([
      'entity_type',
      'bundle',
      'entity_id',
      'revision_id',
      'selected',
    ], $fields);

    return $insert->execute();
  }

  /**
   * Insert an entry into the database.
   *
   * @param string $entity_type
   *   Variable containing the entity type machine name.
   * @param string $bundle
   *   Variable containing the bundle machine name.
   * @param int $entity_id
   *   Variable containing the entity id.
   * @param int $revision_id
   *   Variable containing the revision id.
   * @param int $selected
   *   Variable containing default title display selection.
   *
   * @see Drupal\Core\Database\Connection::insert()
   *
   * @return int
   *   Inserted entity id.
   */
  public function upsertEntityRelationship($entity_type = 'node', $bundle = 'node', $entity_id = NULL, $revision_id = NULL, $selected = 1) {

    $fields = [
      'entity_type' => $entity_type,
      'bundle' => $bundle,
      'entity_id' => (int) $entity_id,
      'revision_id' => (int) $revision_id,
    ];

    $selected = (bool) $selected;
    $fields['selected'] = (int) $selected;

    $insert = $this->connection->merge('layout_builder_additions_title_display_entity')
      ->insertFields($fields)
      ->updateFields(
        [
          'revision_id' => (int) $revision_id,
          'selected' => $fields['selected'],
        ]
      )
      ->key(
              [
                'entity_type' => $entity_type,
                'bundle' => $bundle,
                'entity_id' => $entity_id,
              ]
          );

    return $insert->execute();
  }

  /**
   * Delete an entry from the database.
   *
   * @param string $entity_type
   *   Variable containing the entity type machine name.
   * @param string $bundle
   *   Variable containing the bundle machine name.
   * @param int $entity_id
   *   A variable containing the entity id of the entry to delete.
   *
   * @see Drupal\Core\Database\Connection::delete()
   */
  public function deleteEntityRelationship($entity_type = 'node', $bundle = 'node', $entity_id = NULL) {
    $this->connection->delete('layout_builder_additions_title_display_entity')
      ->condition('entity_type', $entity_type)
      ->condition('bundle', $bundle)
      ->condition('entity_id', $entity_id)
      ->execute();
  }

}
