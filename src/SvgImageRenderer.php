<?php

namespace Drupal\Svg;

class SvgImageRenderer extends SvgBaseRenderer
{
  /**
   * Resolves the path to the svg file by replacing @theme tokens and name mappings.
   *
   * @param $path
   * @return mixed
   */
  public function generateSvgImage($uri, $config = []) {
    $default = [
      'offsetX' => 0,
      'offsetY' => 0,
      'class' => '',
      'alt' => ''
    ];

    $config = array_merge($default, (array) $config);

    $this->parseSvg($uri);

    $image = $this->dom->createElement('img');
    $image->setAttribute('class', $config['class']);
    if (!empty($config['alt'])) {
      $image->setAttribute('alt', $config['alt']);
    }

    $this->dom->appendChild($image);

    xdebug_break();

    dpm('hallo');

    return $this->dom->saveHTML();
  }
}
