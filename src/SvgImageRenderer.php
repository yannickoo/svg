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
  public function generateSvgImage($uri, $config = []) {
    $default = [
      'offsetX' => 0,
      'offsetY' => 0,
      'class' => '',
      'alt' => '',
    ];

    $config = array_merge($default, (array) $config);

    $this->parseSvg($uri);

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
