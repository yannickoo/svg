<?php

/**
 * @file
 * Contains \Drupal\svg\SvgInlineRenderer.
 */

namespace Drupal\svg;

class SvgInlineRenderer extends SvgBaseRenderer {

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

    // Create new DOM document for inline SVG.
    $dom = new \DOMDocument();
    $svg = $dom->createElement('svg');

    $view_box = $this->viewBox;

    // Manipulate viewBox for given options.
    foreach (['x', 'y', 'width', 'height'] as $type) {
      if ($options['viewBox'][$type] !== NULL) {
        $view_box[$type] = $options['viewBox'][$type];
      }
    }

    // Set viewBox attribute only if 4 items (x, y, width, height) are given.
    if (count($view_box) == 4) {
      $view_box['width'] += $options['adjustWidth'];
      $view_box['height'] += $options['adjustHeight'];

      // Transform viewBox array into string.
      $view_box_attr = implode(' ', $view_box);

      $svg->setAttribute('viewBox', $view_box_attr);
    }

    // Setting SVG attributes.
    foreach ($options['attributes'] as $attribute => $value) {
      if ($value) {
        $svg->setAttribute($attribute, $value);
      }
    }

    $content = $dom->createDocumentFragment();
    $content->appendXML($this->svgContent->html());
    $svg->appendChild($content);

    $dom->appendChild($svg);

    return trim($dom->saveHTML());
  }

}
