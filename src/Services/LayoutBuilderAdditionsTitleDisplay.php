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
   */
  public function getNode($nid = NULL) {
    $nodes = $this->connection->select('layout_builder_additions_title_display_node', 'titleon')
      ->fields('titleon');

    if (!is_null($nid)) {
      $nodes->condition('nid', $nid);
    }

    $results = $nodes->execute();
    $saved_node_relations = [];
    foreach ($results as $id => $result) {
      if (is_array($results) && count($results) > 1) {
        $saved_node_relations[$id] = $result;
      }
      else {
        $saved_node_relations = $result;
      }
    }
    return $saved_node_relations;
  }

  /**
   * Insert an entry into the database.
   *
   * @param string $bundle
   *   Variable containing the bundle to add.
   *
   * @see \Drupal\Core\Database\Connection::insert()
   *
   * @throws
   *
   * @return object
   */
  public function insertBundle($bundle) {
    $fields = [
      'bundle' => $bundle,
    ];

    $insert = $this->connection->insert('layout_builder_additions_title_bundles');
    $insert->fields(['bundle'], $fields);
    return $insert->execute();
  }

  /**
   * Insert an entry into the database.
   *
   * @param int $nid
   *   Variable containing node id.
   *
   * @param int $selected
   *   Variable containing default itle display selection.
   *
   * @see Drupal\Core\Database\Connection::insert()
   *
   * @throws
   *
   * @return
   */
  public function insertDisplayNodeRelation($nid, $selected) {

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
   *
   * @param int $selected
   *   Variable containing default title display selection.
   *
   * @see Drupal\Core\Database\Connection::insert()
   *
   * @throws
   *
   * @return
   */
  public function upsertTitleDisplayNodeSelection($nid, $selected) {

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
  public function deleteTitleDisplayNodeRelation($nid) {
    $this->connection->delete('layout_builder_additions_title_display_node')
      ->condition('nid', $nid)
      ->execute();
  }

}
