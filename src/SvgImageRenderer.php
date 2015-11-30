<?php

namespace Drupal\svg;

use Drupal\Core\Template\Attribute;

class SvgImageRenderer extends SvgBaseRenderer
{
  /**
   * Generate simple SVG image tag.
   *
   * @param string $uri
   * @param array $options
   * @return mixed
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
