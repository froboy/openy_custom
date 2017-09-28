<?php

namespace Drupal\openy_campaign\Theme;

use Drupal\Core\Theme\ThemeNegotiatorInterface;
use Drupal\Core\Routing\RouteMatchInterface;

class ThemeNegotiator implements ThemeNegotiatorInterface {

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {

    $possible_routes = array(
      'entity.node.canonical',
    );

    if (in_array($route_match->getRouteName(), $possible_routes)) {
      $node = $route_match->getParameter('node');

      // Check if requested node uses in campaign.
      $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'campaign');
      $orGroup = $query->orConditionGroup()
        ->condition('field_campaign_pages', $node->id(), 'IN')
        ->condition('field_my_progress_page', $node->id())
        ->condition('field_rules_prizes_page', $node->id())
        ->condition('field_pause_landing_page', $node->id());
      $campaignLandingPages = $query->condition($orGroup)->execute();

      if (!empty($campaignLandingPages)) {
        return true;
      }

      return ($node->getType() === 'campaign');
    }

    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    // Here return the actual theme name.
    return 'openy_campaign_theme';
  }
}