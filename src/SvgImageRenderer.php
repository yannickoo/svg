<?php

namespace Drupal\svg;

class SvgImageRenderer extends SvgBaseRenderer
{
  /**
   * Generate simple SVG image tag.
   *
   * @param string $uri
   * @param array $config
   * @return mixed
   */
  public function generate($uri, $options = []) {
    $defaults = [
      'offsetX' => 0,
      'offsetY' => 0,
      'class' => '',
      'alt' => '',
    ];

    $options = array_merge($defaults, (array) $options);

    $this->resolveUri($uri);

    $image = $this->dom->createElement('img');
    $image->setAttribute('src', $this->href);

    // Adding attributes to image tag.
    foreach (['class', 'alt'] as $attribute) {
      if (!empty($config[$attribute])) {
        $image->setAttribute($attribute, $config[$attribute]);
      }
    }

    $this->dom->appendChild($image);

    return $this->dom->saveHTML();
  }
}
