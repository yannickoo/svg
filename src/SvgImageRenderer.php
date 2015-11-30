<?php

namespace Drupal\svg;

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
    $dom = new \DOMDocument();

    $defaults = [
      'attributes' => [],
    ];

    $options = array_merge($defaults, (array) $options);

    $this->resolveUri($uri);

    $image = $dom->createElement('img');
    $image->setAttribute('src', $this->href);

    // Setting image attributes.
    foreach ($options['attributes'] as $attribute => $value) {
      if ($value) {
        $image->setAttribute($attribute, $value);
      }
    }

    $dom->appendChild($image);

    return trim($dom->saveHTML());
  }
}
