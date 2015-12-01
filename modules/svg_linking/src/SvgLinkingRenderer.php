<?php

/**
 * @file
 * Contains \Drupal\svg_linking\SvgLinkingRenderer.
 */

namespace Drupal\svg_linking;

use Drupal\svg\SvgBaseRenderer;

class SvgLinkingRenderer extends SvgBaseRenderer {

  /**
   * Renders an inline SVG image.
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
    $defaults = [
      'adjustWidth' => 0,
      'adjustHeight' => 0,
      'attributes' => [
        'width' => NULL,
        'height' => NULL,
        'class' => '',
      ],
      'viewBox' => [
        'width' => NULL,
        'height' => NULL,
        'x' => NULL,
        'y' => NULL,
      ],
    ];

    $options = array_merge($defaults, (array) $options);

    $this->resolveUri($uri);

    if (!$this->parse($uri)) {
      return '';
    }

    // Create new DOM document for SVG with use element.
    $dom = new \DOMDocument();
    $svg = $dom->createElement('svg');

    $view_box = $this->viewBox;

    if (count($view_box) == 4) {
      // Only use the dimensions of the linked svg and not the position.
      $view_box['x'] = 0;
      $view_box['y'] = 0;

      // Manipulate viewBox for given options.
      foreach (['x', 'y', 'width', 'height'] as $type) {
        if ($options['viewBox'][$type] !== NULL) {
          $view_box[$type] = $options['viewBox'][$type];
        }
      }

      $view_box['width'] += $options['adjustWidth'];
      $view_box['height'] += $options['adjustHeight'];

      // Transform viewBox array into string.
      $view_box_attr = implode(' ', $view_box);

      $svg->setAttribute('viewBox', $view_box_attr);
    }

    // Add use element to link svg.
    $use = $dom->createElement('use');
    $use->setAttribute('xlink:href', $this->href);
    $svg->appendChild($use);

    // Setting SVG attributes.
    foreach ($options['attributes'] as $attribute => $value) {
      if ($value) {
        $svg->setAttribute($attribute, $value);
      }
    }

    $dom->appendChild($svg);

    return trim($dom->saveHTML());
  }
}
