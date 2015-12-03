<?php

/**
 * @file
 * Contains \Drupal\svg\SvgImageRenderer.
 */

namespace Drupal\svg;

use Drupal\Core\Template\Attribute;

/**
 * Class SvgImageRenderer.
 *
 * @package Drupal\svg
 */
class SvgImageRenderer extends SvgBaseRenderer {

  /**
   * Generate simple SVG image tag.
   *
   * @param string $uri
   *   URI of SVG image which should be rendered.
   * @param array $options
   *   Options passed to renderer.
   *
   * @return string
   *   HTML code for SVG image.
   */
  public function generate($uri, $options = []) {
    $defaults = ['attributes' => []];
    $options = array_merge($defaults, (array) $options);

    $this->resolveUri($uri);

    $options['attributes']['src'] = $this->href;
    // Remove empty attributes.
    $options['attributes'] = array_filter($options['attributes']);

    return '<img' . new Attribute($options['attributes']) . '>';
  }

}
