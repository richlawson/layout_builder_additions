<?php

namespace Drupal\layout_builder_additions\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Replace the static title with a title callback on the Layout Builder
    // block forms.
    if ($route = $collection->get('layout_builder.add_block')) {
      $route->setDefaults([
        '_form' => $route->getDefault('_form'),
        '_title_callback' => '\Drupal\layout_builder_additions\LayoutBuilderBlockFormTitle::addBlock',
      ]);
    }
    if ($route = $collection->get('layout_builder.update_block')) {
      $route->setDefaults([
        '_form' => $route->getDefault('_form'),
        '_title_callback' => '\Drupal\layout_builder_additions\LayoutBuilderBlockFormTitle::updateBlock',
      ]);
    }
  }

}
