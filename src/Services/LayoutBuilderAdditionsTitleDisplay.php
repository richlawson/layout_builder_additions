<?php

namespace Drupal\layout_builder_additions\Services;

use Drupal\Core\Database\Connection;

/**
 *
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
   * Get node and title display relation.
   *
   * @param int $nid
   *   Variable containing node id.
   *
   * @see Drupal\Core\Database\Connection::select()
   *
   * @return array
   *   Array containing title display node relation.
   */
  public function getNode($nid = NULL) {
    $nodes = $this->connection->select('layout_builder_additions_title_display_node', 'titleon')
      ->fields('titleon');

    if (!is_null($nid)) {
      $nodes->condition('nid', $nid);
    }

    $results = $nodes->execute();
    $saved_node_relation = [];
    foreach ($results as $id => $result) {
      if (is_array($results) && count($results) > 1) {
        $saved_node_relation[$id] = $result;
      }
      else {
        $saved_node_relation = $result;
      }
    }
    return $saved_node_relation;
  }

  /**
   * Get title display bundles from the database.
   *
   * @see Drupal\Core\Database\Connection::select()
   *
   * @return array
   *   Array containing title display bundle relation.
   */
  public function getBundle($bundle = NULL) {
    $bundles = $this->connection->select('layout_builder_additions_title_display_bundle', 'bundle');

    if (!is_null($bundle)) {
      $bundles->condition('bundle', $bundle);
    }

    $results = $bundles->execute();
    $bundle = [];
    foreach ($results as $id => $result) {
      if (is_array($results) && count($results) > 1) {
        $saved_bundle_relation[$id] = $result;
      }
      else {
        $saved_bundle_relation = $result;
      }
    }
    return $saved_bundle_relation;
  }

  /**
   * Insert an entry into the database.
   *
   * @param string $bundle
   *   Variable containing the bundle to add.
   *
   * @see \Drupal\Core\Database\Connection::insert()
   */
  public function insertBundle($bundle) {
    $fields = [
      'bundle' => $bundle,
    ];

    $insert = $this->connection->insert('layout_builder_additions_title_display_bundle');
    $insert->fields(['bundle'], $fields);
    return $insert->execute();
  }

  /**
   * Delete an entry from the database.
   *
   * @param string $bundle
   *   A variable containing the bundle to delete.
   *
   * @see Drupal\Core\Database\Connection::delete()
   */
  public function deleteBundle($bundle) {
    $this->connection->delete('layout_builder_additions_title_display_bundle')
      ->condition('bundle', $bundle)
      ->execute();
  }

  /**
   * Insert an entry into the database.
   *
   * @param int $nid
   *   Variable containing node id.
   * @param int $selected
   *   Variable containing default itle display selection.
   *
   * @see Drupal\Core\Database\Connection::insert()
   *
   * @return int
   *   Inserted nid.
   */
  public function insertNodeRelationship($nid, $selected) {

    $fields = [
      'nid' => (int) $nid,
    ];

    if ($selected) {
      $selected = (bool) $selected;
      $fields['selected'] = (int) $selected;
    }

    $insert = $this->connection->insert('layout_builder_additions_title_display_node');
    $insert->fields(['nid', 'selected'], $fields);

    return $insert->execute();
  }

  /**
   * Insert an entry into the database.
   *
   * @param int $nid
   *   Variable containing node id.
   * @param int $selected
   *   Variable containing default title display selection.
   *
   * @see Drupal\Core\Database\Connection::insert()
   *
   * @return int
   *   Inserted nid.
   */
  public function upsertNodeRelationship($nid, $selected) {

    $fields = [
      'nid' => (int) $nid,
    ];

    $selected = (bool) $selected;
    $fields['selected'] = (int) $selected;

    $insert = $this->connection->merge('layout_builder_additions_title_display_node')
      ->insertFields($fields)
      ->updateFields(
        [
          'selected' => $fields['selected'],
        ]
      )
      ->key(
              [
                'nid' => $nid,
              ]
          );

    return $insert->execute();
  }

  /**
   * Delete an entry from the database.
   *
   * @param int $nid
   *   A variable containing the node ID of the entry to delete.
   *
   * @see Drupal\Core\Database\Connection::delete()
   */
  public function deleteNodeRelationship($nid) {
    $this->connection->delete('layout_builder_additions_title_display_node')
      ->condition('nid', $nid)
      ->execute();
  }

}
